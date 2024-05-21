const express = require("express");
const router = express.Router();
const { Authentication } = require("../middleware/authMiddleware");
const ArticleController = require("../controllers/articleController");

router.post("/article", Authentication, ArticleController.createArticle);
router.put("/article", Authentication, ArticleController.updateArticle);
router.get("/article/:id", Authentication, ArticleController.listOneArticle);
router.get("/article", Authentication, ArticleController.listAllArticle);
router.delete("/article", Authentication, ArticleController.deleteArticle);

module.exports = router;
