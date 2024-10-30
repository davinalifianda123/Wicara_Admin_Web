require('dotenv').config();
const express = require('express');
const mysql = require('mysql2');

const app = express();
const port = 3000;

// Create a connection pool
const db = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
});

// Endpoint to get counts for each type of kejadian
app.get('/api/jumlah-kejadian', (req, res) => {
  const query = `
    SELECT 
      jk.nama_kejadian AS kejadian, 
      COUNT(k.id_kejadian) AS count 
    FROM kejadian k
    JOIN jenis_kejadian jk ON k.id_jenis_kejadian = jk.id_jenis_kejadian
    GROUP BY k.id_jenis_kejadian;
  `;

  db.query(query, (err, results) => {
    if (err) {
      console.error(err);
      res.status(500).json({ error: 'Database error' });
    } else {
      res.json(results);
    }
  });
});

// Start server
app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
