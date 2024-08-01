const whatsapp = require("wa-session-custom");
const ValidationError = require("../../utils/error");
const { responseSuccessWithData } = require("../../utils/response");
const mysql = require("mysql2");
const { config } = require("dotenv");
config();

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