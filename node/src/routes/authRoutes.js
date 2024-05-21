const express = require("express");
const router = express.Router();
const LoginController = require("../controllers/authController");
const { CreateAuthMiddleware } = require("../middleware/authMiddleware");

router.post("/login", CreateAuthMiddleware, LoginController.loginUser);

module.exports = router;
