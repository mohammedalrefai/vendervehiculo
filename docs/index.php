<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Vender tu vehículo | vendervehiculo.es</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; }
    header, footer { background: #003366; color: #fff; padding: 16px 0; text-align: center; }
    .container { max-width: 600px; background: #fff; margin: 40px auto; border-radius: 8px; box-shadow: 0 2px 8px #ccc; padding: 24px; }
    h1 { margin-bottom: 24px; }
    .form-section { margin-bottom: 24px; }
    label { display: block; margin-bottom: 8px; font-weight: bold; }
    input, select { width: 100%; padding: 8px; margin-bottom: 16px; border: 1px solid #ddd; border-radius: 4px; }
    button { background: #007acc; color: #fff; border: none; padding: 12px 24px; border-radius: 4px; font-size: 16px; cursor: pointer; }
    button:hover { background: #005999; }
    .required { color: #c00; font-size: 14px; margin-left: 4px }
    .success { color: green; margin-bottom: 16px; }
    .error { color: red; margin-bottom: 16px; }
  </style>
</head>
<body>
  <header>
    <h2>VenderVehiculo.es</h2>
    <p>Vende tu coche de forma fácil, rápida y segura</p>
  </header>
  <div class="container">
    <h1>Formulario de venta de vehículo</h1>

    <?php
    $success = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Recoger y sanitizar datos del formulario
      $fields = [
        'marca', 'modelo', 'año', 'combustible', 'tipo', 'transmision', 'version', 'kilometros', 'matricula',
        'nombre', 'telefono', 'email', 'provincia', 'localidad', 'codigo_postal'
      ];
      $data = [];
      foreach ($fields as $field) {
        if (empty($_POST[$field])) {
          $error = 'Por favor, rellene todos los campos obligatorios.';
          break;
        }
        $data[$field] = htmlspecialchars(trim($_POST[$field]));
      }
      if (!$error) {
        // Preparar contenido email
        $message = "Se ha recibido una nueva solicitud de venta de vehículo:\n\n";
        foreach ($data as $key => $value) {
          $keyReadable = ucfirst(str_replace('_', ' ', $key));
          $message .= "$keyReadable: $value\n";
        }

        $to = 'example@test.com';
        $subject = 'Nueva solicitud de venta de vehículo';
        $headers = "From: no-reply@vendervehiculo.es\r\nReply-To: " . $data['email'];

        if (mail($to, $subject, $message, $headers)) {
          $success = 'Gracias por enviar la información. Nos pondremos en contacto pronto.';
        } else {
          $error = 'Hubo un problema enviando su solicitud. Inténtelo de nuevo más tarde.';
        }
      }
    }
    ?>

    <?php if ($success): ?>
      <p class="success"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <div class="form-section">
        <label>Marca <span class="required">*</span></label>
        <input type="text" name="marca" value="<?= $_POST['marca'] ?? '' ?>" required>
        <label>Modelo <span class="required">*</span></label>
        <input type="text" name="modelo" value="<?= $_POST['modelo'] ?? '' ?>" required>
        <label>Año <span class="required">*</span></label>
        <input type="number" name="año" min="1900" max="2025" value="<?= $_POST['año'] ?? '' ?>" required>
        <label>Combustible <span class="required">*</span></label>
        <select name="combustible" required>
          <option value="">Seleccione...</option>
          <?php
            $combustibles = ['gasolina', 'diésel', 'híbrido', 'eléctrico', 'glp', 'gnc'];
            foreach ($combustibles as $c) {
              $selected = (isset($_POST['combustible']) && $_POST['combustible'] === $c) ? 'selected' : '';
              echo "<option value=\"$c\" $selected>" . ucfirst($c) . "</option>";
            }
          ?>
        </select>
        <label>Tipo <span class="required">*</span></label>
        <select name="tipo" required>
          <option value="">Seleccione...</option>
          <?php
            $tipos = ['berlina','familiar','suv','coupé','cabrio','monovolumen','pickup','furgoneta'];
            foreach ($tipos as $t) {
              $selected = (isset($_POST['tipo']) && $_POST['tipo'] === $t) ? 'selected' : '';
              echo "<option value=\"$t\" $selected>" . ucfirst($t) . "</option>";
            }
          ?>
        </select>
        <label>Transmisión <span class="required">*</span></label>
        <select name="transmision" required>
          <option value="">Seleccione...</option>
          <?php
            $transmisiones = ['manual', 'automática'];
            foreach ($transmisiones as $tr) {
              $selected = (isset($_POST['transmision']) && $_POST['transmision'] === $tr) ? 'selected' : '';
              echo "<option value=\"$tr\" $selected>" . ucfirst($tr) . "</option>";
            }
          ?>
        </select>
        <label>Versión <span class="required">*</span></label>
        <input type="text" name="version" value="<?= $_POST['version'] ?? '' ?>" required>
        <label>Kilómetros <span class="required">*</span></label>
        <input type="number" name="kilometros" min="0" value="<?= $_POST['kilometros'] ?? '' ?>" required>
        <label>Matrícula <span class="required">*</span></label>
        <input type="text" name="matricula" value="<?= $_POST['matricula'] ?? '' ?>" required>
      </div>
      <div class="form-section">
        <label>Nombre <span class="required">*</span></label>
        <input type="text" name="nombre" value="<?= $_POST['nombre'] ?? '' ?>" required>
        <label>Teléfono <span class="required">*</span></label>
        <input type="tel" name="telefono" value="<?= $_POST['telefono'] ?? '' ?>" required>
        <label>Email <span class="required">*</span></label>
        <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
        <label>Provincia <span class="required">*</span></label>
        <input type="text" name="provincia" value="<?= $_POST['provincia'] ?? '' ?>" required>
        <label>Localidad <span class="required">*</span></label>
        <input type="text" name="localidad" value="<?= $_POST['localidad'] ?? '' ?>" required>
        <label>Código postal <span class="required">*</span></label>
        <input type="text" name="codigo_postal" value="<?= $_POST['codigo_postal'] ?? '' ?>" required>
      </div>
      <button type="submit">Enviar solicitud</button>
    </form>
  </div>
  <footer>
    <p>&copy; 2025 vendervehiculo.es - Todos los derechos reservados</p>
  </footer>
</body>
</html>
