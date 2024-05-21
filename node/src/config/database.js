require("dotenv").config();
module.exports = {
  dialect: process.env.DB_DIALECT || "mysql",
  port: +process.env.DB_PORT || 3306,
  host: process.env.DB_HOST || "db",
  username: process.env.DB_USERNAME || "root",
  password: process.env.DB_PASSWORD || "12345",
  database: process.env.DB_DATABASE || "project_deanta",
  define: {
    timestamps: true,
    underscored: true,
  },
};
