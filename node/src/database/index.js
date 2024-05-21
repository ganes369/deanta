const Sequelize = require("sequelize");
const dbConfig = require("../config/database");

const User = require("../models/User");
const Project = require("../models/Project");
const BlackList = require("../models/BlackList");
const Article = require("../models/Article");

const connection = new Sequelize(dbConfig);

User.init(connection);
Project.init(connection);
Article.init(connection);
BlackList.init(connection);

User.associate(connection.models);
Project.associate(connection.models);
Article.associate(connection.models);
module.exports = connection;
