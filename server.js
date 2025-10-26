// Simple Express backend to accept submissions and store them.
// For demo purposes: saves uploaded images to ./uploads and submissions to submissions.json
//
// Note: This is a minimal example. For production you should:
// - Add authentication, rate limiting and spam protection
// - Validate and sanitize input server-side
// - Store files in a durable storage (S3, CDN) and serve them securely
// - Add HTTPS and environment-based configuration

const express = require('express');
const multer = require('multer');
const fs = require('fs');
const path = require('path');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.static(path.join(__dirname))); // serve web/ files if placed here

// Ensure directories
const UPLOADS = path.join(__dirname, 'uploads');
if (!fs.existsSync(UPLOADS)) fs.mkdirSync(UPLOADS, { recursive: true });

// Multer config (store files on disk)
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, UPLOADS),
  filename: (req, file, cb) => {
    const unique = Date.now() + '-' + Math.round(Math.random()*1e9);
    const clean = (file.originalname || 'file').replace(/\s+/g,'-').replace(/[^a-zA-Z0-9.\-_]/g,'');
    cb(null, unique + '-' + clean);
  }
});
const upload = multer({ storage, limits: { fileSize: 5 * 1024 * 1024 } }); // 5MB per file

// Endpoint to receive the form
app.post('/api/submit', upload.array('photos', 6), (req, res) => {
  try {
    const body = req.body;
    const files = (req.files || []).map(f => ({
      originalname: f.originalname,
      filename: f.filename,
      path: `/uploads/${f.filename}`,
      size: f.size
    }));

    const submission = {
      id: 's_' + Date.now(),
      created_at: new Date().toISOString(),
      vehicle: {
        make: body.make || '',
        model: body.model || '',
        year: body.year || '',
        mileage: body.mileage || '',
        fuel: body.fuel || '',
        transmission: body.transmission || '',
        color: body.color || '',
        vin: body.vin || '',
        price: body.price || '',
        description: body.description || ''
      },
      contact: {
        name: body.name || '',
        phone: body.phone || '',
        email: body.email || '',
        location: body.location || ''
      },
      files
    };

    // Append to submissions.json
    const DB = path.join(__dirname, 'submissions.json');
    let dbArr = [];
    if (fs.existsSync(DB)) {
      try {
        dbArr = JSON.parse(fs.readFileSync(DB));
      } catch(_) { dbArr = []; }
    }
    dbArr.push(submission);
    fs.writeFileSync(DB, JSON.stringify(dbArr, null, 2));

    // Return success
    res.json({ ok: true, id: submission.id });
  } catch (err) {
    console.error('submit error', err);
    res.status(500).json({ ok: false, error: 'Internal error' });
  }
});

// Serve uploaded files (readonly)
app.use('/uploads', express.static(UPLOADS));

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Form server running on http://localhost:${PORT}`);
});