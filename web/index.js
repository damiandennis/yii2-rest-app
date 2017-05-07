const path = require("path");
const express = require('express');
const app = express();

app.use(express.static(__dirname + '/ng2/dist'));

app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, '/ng2/dist/index.html'));
});

app.listen(80);
