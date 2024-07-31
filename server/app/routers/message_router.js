const { Router } = require("express");
const {
  sendMessage,
  sendBulkMessage,
  sendCustom,
} = require("../controllers/message_controller");
const MessageRouter = Router();

MessageRouter.all("/send-message", sendMessage);
MessageRouter.all("/send-bulk-message", sendBulkMessage);
MessageRouter.all("/send-custom", sendCustom);

module.exports = MessageRouter;
