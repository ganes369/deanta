const express = require("express");
const router = express.Router();
const { Authentication } = require("../middleware/authMiddleware");
const ProjectController = require("../controllers/projectController");

router.post("/project", Authentication, ProjectController.createProject);
router.put("/project", Authentication, ProjectController.updateProject);

module.exports = router;
