const express = require("express");
const router = express.Router();
const UserController = require("../controllers/userController");
const {
  CreateAuthMiddleware,
  Authentication,
} = require("../middleware/authMiddleware");

// Route create user
router.post("/users", CreateAuthMiddleware, UserController.createUser);
router.put("/users", Authentication, UserController.updateUser);

router.get("/users", Authentication, UserController.getAllUsers);

module.exports = router;
