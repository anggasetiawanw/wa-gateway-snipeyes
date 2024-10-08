const { Router } = require("express");
const {
  sendMessage,
  sendBulkMessage,
  sendCustom,
  sendLog,
  getLog,
} = require("../controllers/message_controller");
const MessageRouter = Router();

MessageRouter.all("/send-message", sendMessage);
MessageRouter.all("/send-bulk-message", sendBulkMessage);
MessageRouter.all("/send-custom", sendCustom);
MessageRouter.all("/log", sendLog);
MessageRouter.all("/logs", getLog);

module.exports = MessageRouter;
