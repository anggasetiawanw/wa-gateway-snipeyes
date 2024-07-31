const { Router } = require("express");
const MessageRouter = require("./message_router");
const SessionRouter = require("./session_router");

const MainRouter = Router();

MainRouter.post('/log', (req, res) => {
    const ip = req.headers['x-forwarded-for'] || req.socket.remoteAddress;
    console.log(ip);
    console.log(req.body);
    // const userAgent = req.body.userAgent;
    // const location = req.body.location;
  
    // const logEntry = `IP: ${ip}, User Agent: ${userAgent}, Location: ${JSON.stringify(location)}\n`;
  
    // Save the log entry to a file (or database)
    // fs.appendFile('user_logs.txt', logEntry, (err) => {
    //   if (err) console.error('Error writing to file:', err);
    // });
  
    res.status(200).send('Data received');
  });
MainRouter.use(SessionRouter);
MainRouter.use(MessageRouter);

module.exports = MainRouter;
