const express = require("express");
const fs = require("fs");
const https = require("https"); // Import https module
const app = express();
const cors = require("cors"); // Import the cors package

const credentials = {
  // Since this certs need to be owned by root, we need to npm start this as root
  key: fs.readFileSync("/etc/letsencrypt/live/defaria.com/privkey.pem"),
  cert: fs.readFileSync("/etc/letsencrypt/live/defaria.com/cert.pem"),
};

app.use(cors()); // Enable CORS for all routes
app.use(express.json());

app.post("/log-playback", (req, res) => {
  const fileType = req.body.fileType;
  const IPAddr = req.body.IPAddr;
  const file = req.body.file;
  const msg = req.body.msg;

  const logEntry = `${new Date().toISOString()}: IP: ${IPAddr} ${fileType} ${file} ${msg}\n`;

  fs.appendFile("/web/pm/playback.log", logEntry, (err) => {
    if (err) {
      console.error("Error writing to log file:", err);
      res.sendStatus(500); // Internal Server Error
    } else {
      res.sendStatus(200); // OK
    }
  });
});

const httpsServer = https.createServer(credentials, app);

httpsServer.listen(3000, "0.0.0.0", () => {
  console.log("HTTPS Server listening on port 3000");
});
