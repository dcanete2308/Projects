import express from "express";
import "./db/connection.js";
import userRoutes from "./routes/user.routes.js";
import noteRoutes from "./routes/note.routes.js";
import cookieParser from "cookie-parser";
import cors from "cors";

const app = express();
app.use(express.json());
app.use(cookieParser());

app.use(
  cors({
    origin: "http://localhost:5173",
    credentials: true,
  })
);

app.get("/", (req, res) => {
  res.json("Test 1");
});
app.listen(8080, () => {
  console.log("Server is running on port 8080.");
});

app.use("/", userRoutes);
app.use("/", noteRoutes);
