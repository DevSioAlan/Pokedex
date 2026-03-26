<!DOCTYPE html>
<?php
require_once 'functions.php';

$name = $_GET["name"] ?? null;

if (!$name) {
    header("Location: index.php");
    exit();
}

$db = connect_to_db();
$pokemon = get_pokemon($name, $db);

if (!$pokemon) {
    header("Location: index.php?err=Pokemon not found");
    exit();
}

$stats = [
    'HP' => ['val' => $pokemon['hp'], 'max' => 255, 'label' => 'PV'],
    'ATK' => ['val' => $pokemon['atk'], 'max' => 190, 'label' => 'Attaque'],
    'DEF' => ['val' => $pokemon['def'], 'max' => 230, 'label' => 'Défense'],
    'SPA' => ['val' => $pokemon['spe_atk'], 'max' => 194, 'label' => 'Atq Spé'],
    'SPD' => ['val' => $pokemon['spe_def'], 'max' => 230, 'label' => 'Déf Spé'],
    'SPE' => ['val' => $pokemon['speed'], 'max' => 180, 'label' => 'Vitesse'],
];

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pokemon["name"]) ?> - Pokédex</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="detail-card">
            <div class="detail-header <?= strtolower($pokemon["type1"]) ?>">
                <a href="index.php" class="back-link" style="color: white; position: absolute; left: 20px; top: 20px;">← Retour</a>
                <h1><?= htmlspecialchars($pokemon["name"]) ?></h1>
                <?php if ($pokemon["nickname"]): ?>
                    <div class="nickname-display">"<?= htmlspecialchars($pokemon["nickname"]) ?>"</div>
                <?php endif; ?>
                <div class="types" style="margin-top: 15px;">
                    <span class="type <?= strtolower($pokemon["type1"]) ?>"><?= htmlspecialchars($pokemon["type1"]) ?></span>
                    <?php if ($pokemon["type2"]): ?>
                        <span class="type <?= strtolower($pokemon["type2"]) ?>"><?= htmlspecialchars($pokemon["type2"]) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-body">
                <div class="detail-img-container">
                    <img src="<?= htmlspecialchars($pokemon["img_url"]) ?>" alt="<?= htmlspecialchars($pokemon["name"]) ?>">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Taille</span>
                            <span class="info-value"><?= htmlspecialchars($pokemon["height"] ?? '0') ?> m</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Poids</span>
                            <span class="info-value"><?= htmlspecialchars($pokemon["weight"] ?? '0') ?> kg</span>
                        </div>
                    </div>
                </div>

                <div class="stats-container">
                    <?php foreach ($stats as $key => $stat): ?>
                        <div class="stat-row">
                            <div class="stat-label"><?= $stat['label'] ?></div>
                            <div class="stat-bar-bg">
                                <div class="stat-bar-fill" style="width: <?= ($stat['val'] / $stat['max']) * 100 ?>%"></div>
                            </div>
                            <div class="stat-value"><?= $stat['val'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="actions-section">
                <form action="actions/update_pokemon_nickname.php" method="POST" class="form-group" style="justify-content: center;">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($pokemon['name']) ?>">
                    <input type="text" name="nickname" value="<?= htmlspecialchars($pokemon['nickname'] ?? '') ?>" placeholder="Surnom">
                    <button type="submit">Renommer</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
