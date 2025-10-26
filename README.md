# Formulario "Vender mi coche" — demo

Contenido:
- web/index.html — formulario responsive (Bootstrap)
- web/styles.css — pequeños estilos
- web/app.js — manejo de imágenes, validación y envío (fetch)
- web/server.js — ejemplo de backend Express que guarda envíos y fotos
- web/package.json — dependencias y script

Cómo ejecutar (local):
1. Instala Node.js (>=16).
2. Copia la carpeta `web` en tu proyecto o sitúa los archivos en la raíz.
3. Abre terminal en la carpeta `web` y ejecuta:
   npm install
   npm start
4. Abre http://localhost:3000/index.html

Notas:
- El backend es un ejemplo: guarda datos en `submissions.json` y fotos en `uploads/`.
- Para producción, añade validación/filtrado adicional, almacenamiento duradero (S3), CAPTCHA y políticas de privacidad.
- Si quieres que el formulario envíe por correo, puedo añadir integración con SendGrid, Mailgun o similar (necesitarás la API key).
- Puedo adaptar el diseño para que quede casi idéntico a https://compramostucoche.com/ si quieres replicar estilos/pasos específicos.

What I provided
- A ready-to-run responsive form UI (index.html + styles + JS).
- A minimal Node/Express backend (server.js) that accepts multipart POSTs and stores submissions and uploaded images locally.
- package.json and a README with run instructions.

Next steps (suggested)
- Tell me if you want the form split into a multi-step wizard (like many car-buy sites do), or translated copy edits.
- If you want real email notifications or remote storage (S3), tell me which provider and I will add integration and necessary config examples.
- If you prefer a serverless implementation (Netlify/Functions, Vercel Serverless, or Firebase), I can provide that instead.