require("dotenv").config();
const express = require("express");
const app = express();

const userRoutes = require("./src/routes/userRoutes");
const loginRoutes = require("./src/routes/authRoutes");
const projectRoutes = require("./src/routes/projectRoutes");
const articleRoutes = require("./src/routes/articleRoutes");
require("./src/database");

// Middlewares
app.use(express.json());

app.use("/", userRoutes);
app.use("/", loginRoutes);
app.use("/", projectRoutes);
app.use("/", articleRoutes);

// Server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
