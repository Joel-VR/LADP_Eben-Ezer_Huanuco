<?php
$pageTitle = 'Quiero Entregarme a Jesús';
$pageDesc = 'Da el paso más importante de tu vida';

require_once dirname(__DIR__, 2) . '/src/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrego = isset($_POST['entrego']) ? 1 : 0;
    $reconcilio = isset($_POST['reconcilio']) ? 1 : 0;

    if ($entrego || $reconcilio) {
        db_insert('decisiones', [
            'nombres' => trim($_POST['nombres'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'edad' => trim($_POST['edad'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'departamento' => trim($_POST['departamento'] ?? ''),
            'provincia' => trim($_POST['provincia'] ?? ''),
            'distrito' => trim($_POST['distrito'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'entrego' => $entrego,
            'reconcilio' => $reconcilio,
        ]);

        $decision = ['entrego' => $entrego, 'reconcilio' => $reconcilio];
    } else {
        $error = 'Por favor, marca al menos una opción.';
    }
}

require_once dirname(__DIR__) . '/partials/header.php';
?>

<style>
.decision-hero {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    color: var(--white);
    text-align: center;
    padding: 48px 24px 36px;
    border-radius: 14px;
    margin-bottom: 24px;
}
.decision-hero h2 { font-size: 2rem; margin: 0 0 8px; }
.decision-hero p { font-size: 1.15rem; color: #dbe8ff; max-width: 600px; margin: 0 auto; }
.decision-hero .verse { font-style: italic; margin-top: 16px; font-size: 0.95rem; color: #c8d8f0; }

.form-field {
    margin-bottom: 18px;
}
.form-field label {
    display: block;
    font-weight: 700;
    margin-bottom: 6px;
    color: var(--text);
    font-size: 0.95rem;
}
.form-field input,
.form-field select {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    color: var(--text);
    background: var(--white);
}
.form-field input:focus,
.form-field select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(18, 68, 144, 0.1);
}
.form-field select:disabled {
    background: var(--bg);
    color: var(--muted);
    cursor: not-allowed;
}
.form-field small {
    color: var(--muted);
    font-weight: 400;
}

.decision-checks {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
    padding: 16px;
    background: var(--bg);
    border-radius: 10px;
    margin-bottom: 16px;
}
.decision-checks label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
}
.decision-checks input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: var(--primary);
}

.submit-btn {
    background: var(--accent);
    color: #2c2000;
    padding: 14px 32px;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    transition: background 0.2s;
}
.submit-btn:hover { background: #c18f14; }

.success-message {
    text-align: center;
    padding: 48px 24px;
    background: linear-gradient(135deg, #e8f8ef, #d4efdf);
    border-radius: 14px;
    margin: 24px 0;
}
.success-message .icon { font-size: 4rem; margin-bottom: 16px; }
.success-message h2 { color: #27ae60; font-size: 1.8rem; margin: 0 0 12px; }
.success-message p { font-size: 1.1rem; color: var(--text); max-width: 550px; margin: 0 auto 8px; }
.success-message .verse { font-style: italic; color: var(--muted); margin-top: 20px; }

.error-msg {
    background: #fde8e8;
    color: #c0392b;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    text-align: center;
}

@media (max-width: 768px) {
    .decision-checks { flex-direction: column; gap: 12px; }
    .decision-hero h2 { font-size: 1.5rem; }
}
</style>

<?php if (isset($decision)): ?>

<main class="container">
    <div class="success-message">
        <?php if ($decision['entrego'] && $decision['reconcilio']): ?>
            <div class="icon">🙏✨</div>
            <h2>¡Bienvenido/a a la familia de Dios!</h2>
            <p>Hoy has dado el paso más importante de tu vida. Has entregado tu corazón a Jesús y te has reconciliado con Dios. ¡El cielo celebra contigo!</p>
            <p><em>"De modo que si alguno está en Cristo, nueva criatura es; las cosas viejas pasaron; he aquí todas son hechas nuevas."</em></p>
            <p class="verse">— 2 Corintios 5:17</p>
            <p style="margin-top:24px;">Nos pondremos en contacto contigo para acompañarte en este hermoso camino. ¡No estás solo/a!</p>

        <?php elseif ($decision['entrego']): ?>
            <div class="icon">❤️🔥</div>
            <h2>¡Tu vida ha sido transformada!</h2>
            <p>Hoy Jesús ha entrado en tu corazón. A partir de este momento, eres una nueva criatura. Su amor y su paz te acompañarán cada día.</p>
            <p><em>"Porque de tal manera amó Dios al mundo, que ha dado a su Hijo unigénito, para que todo aquel que en él cree, no se pierda, mas tenga vida eterna."</em></p>
            <p class="verse">— Juan 3:16</p>
            <p style="margin-top:24px;">Nos pondremos en contacto contigo para acompañarte en este hermoso camino. ¡No estás solo/a!</p>

        <?php else: ?>
            <div class="icon">🕊️💛</div>
            <h2>¡La paz de Dios ha llegado a tu corazón!</h2>
            <p>Hoy te has reconciliado con Dios. Su gracia te restaura y su amor te abraza. ¡Él nunca te rechaza, siempre te espera con los brazos abiertos!</p>
            <p><em>"Confesaos vuestras ofensas unos a otros, y orad unos por otros, para que seáis sanados."</em></p>
            <p class="verse">— Santiago 5:16</p>
            <p style="margin-top:24px;">Nos pondremos en contacto contigo para acompañarte. ¡Dios tiene un plan hermoso para tu vida!</p>
        <?php endif; ?>
    </div>
</main>

<?php else: ?>

<main class="container">

    <div class="decision-hero">
        <h2>Quiero Entregarme a Jesús</h2>
        <p>Si sientes en tu corazón que es momento de dar este paso, llena este formulario. Nos encantaría acompañarte.</p>
        <p class="verse">"He aquí, yo estoy a la puerta y llamo; si alguno oye mi voz y abre la puerta, entraré a él." — Apocalipsis 3:20</p>
    </div>

    <div class="card">
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" id="decisionForm">

            <div class="form-field">
                <label for="nombres">Nombres *</label>
                <input type="text" id="nombres" name="nombres" required>
            </div>

            <div class="form-field">
                <label for="apellidos">Apellidos *</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </div>

            <div class="form-field">
                <label for="edad">Edad *</label>
                <input type="number" id="edad" name="edad" min="1" max="120" required>
            </div>

            <div class="form-field">
                <label for="telefono">Teléfono *</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>

            <div class="form-field">
                <label for="direccion">Dirección *</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>

            <div class="form-field">
                <label for="departamento">Departamento *</label>
                <select id="departamento" name="departamento" required>
                    <option value="">— Selecciona un departamento —</option>
                </select>
            </div>

            <div class="form-field">
                <label for="provincia">Provincia *</label>
                <select id="provincia" name="provincia" required disabled>
                    <option value="">— Primero selecciona un departamento —</option>
                </select>
            </div>

            <div class="form-field">
                <label for="distrito">Distrito *</label>
                <select id="distrito" name="distrito" required disabled>
                    <option value="">— Primero selecciona una provincia —</option>
                </select>
            </div>

            <div class="form-field">
                <label for="email">Correo electrónico <small>(opcional)</small></label>
                <input type="email" id="email" name="email">
            </div>

            <div class="decision-checks">
                <label>
                    <input type="checkbox" name="entrego" value="1">
                    Acepto a Cristo como mi Salvador
                </label>
                <label>
                    <input type="checkbox" name="reconcilio" value="1">
                    Me reconcilio
                </label>
            </div>

            <button type="submit" class="submit-btn">Enviar mi decisión</button>
        </form>
    </div>

</main>

<script>
const ubigeo = <?= file_get_contents(dirname(__DIR__, 2) . '/assets/data/ubigeo.json') ?>;

const deptSelect = document.getElementById('departamento');
const provSelect = document.getElementById('provincia');
const distSelect = document.getElementById('distrito');

// Populate departments
Object.keys(ubigeo).sort().forEach(function(dept) {
    const opt = document.createElement('option');
    opt.value = dept;
    opt.textContent = dept;
    deptSelect.appendChild(opt);
});

deptSelect.addEventListener('change', function() {
    provSelect.innerHTML = '<option value="">— Selecciona una provincia —</option>';
    distSelect.innerHTML = '<option value="">— Primero selecciona una provincia —</option>';
    distSelect.disabled = true;

    if (this.value && ubigeo[this.value]) {
        provSelect.disabled = false;
        const provinces = Object.keys(ubigeo[this.value]).sort();
        provinces.forEach(function(prov) {
            const opt = document.createElement('option');
            opt.value = prov;
            opt.textContent = prov;
            provSelect.appendChild(opt);
        });
    } else {
        provSelect.disabled = true;
    }
});

provSelect.addEventListener('change', function() {
    distSelect.innerHTML = '<option value="">— Selecciona un distrito —</option>';

    if (this.value && ubigeo[deptSelect.value] && ubigeo[deptSelect.value][this.value]) {
        distSelect.disabled = false;
        const districts = ubigeo[deptSelect.value][this.value].sort();
        districts.forEach(function(dist) {
            const opt = document.createElement('option');
            opt.value = dist;
            opt.textContent = dist;
            distSelect.appendChild(opt);
        });
    } else {
        distSelect.disabled = true;
    }
});
</script>

<?php endif; ?>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>
