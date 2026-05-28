<?php include_once 'includes/header.php'; ?>

    <main>
        <section class="page-section concept">
            <div class="projet-badge">📍 Phase d'incubation - Formation & Réalité</div>
            <h1 class="page-title">Demain, un lieu unique...</h1>
            
            <p class="projet-intro">
                Né d'un projet d'examen, <strong>Le Repaire des Moustaches</strong> devient une vraie aventure. Mon ambition est de créer à Toulon un espace hybride où le bien-être animal rencontre l'élégance rétro des années 50, porté par une structure associative solidaire.
            </p>

            <div class="piliers">
                <article class="pilier-card">
                    <h3>Les Chats 🐱</h3>
                    <p>Un refuge urbain où nos pensionnaires à moustaches attendent leur famille pour la vie dans une ambiance cosy et bienveillante.</p>
                </article>
                <article class="pilier-card">
                    <h3>Le Rétro 📸</h3>
                    <p>Des ateliers créatifs et des moments de partage célébrant l'univers vintage des années 50 et ses valeurs d'authenticité.</p>
                </article>
                <article class="pilier-card">
                    <h3>Le Social 🤝</h3>
                    <p>Une structure associative (Loi 1901) pour favoriser le bénévolat, la médiation animale et les liens entre les gens au cœur du Var.</p>
                </article>
            </div>

            <div class="feuille-route">
                <h3>Notre Trajectoire</h3>
                <ul>
                    <li><strong>Aujourd'hui :</strong> Structuration du projet en association et test communautaire sur Facebook</li>
                    <li><strong>Été 2026 :</strong> Recherche de partenaires et premiers ateliers pilotes</li>
                    <li><strong>2026-2027 :</strong> Identification d'un lieu à Toulon et développement du réseau</li>
                    <li><strong>À terme :</strong> Ouverture du lieu hybride (refuge + dîner + ateliers solidaires)</li>
                </ul>
            </div>

            <div class="section-texte">
                <h2>L'aventure vous tente ?</h2>
                <p>Nous construisons actuellement notre réseau de partenaires, bénévoles et conseils. Que vous soyez un expert en droit associatif, une photographe passionnée, un restaurateur visionnaire ou simplement un amoureux des chats, votre avis et votre enthousiasme nous intéressent.</p>
                <p><strong>Nous avons le statut idéal pour préparer le terrain sans pression excessive :</strong> une étudiante en formation qui ose croire en ses idées.</p>
                <div class="cta-rejoindre">
                    <a href="formulaire.php">Nous soutenir ou nous conseiller →</a>
                </div>
            </div>
        </section>
    </main>

    <style>
        .projet-badge {
            display: inline-block;
            background-color: var(--vert-menthe);
            color: var(--gris-fonce);
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .projet-intro {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .piliers {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 40px;
            margin-bottom: 60px;
            justify-content: center;
        }

        .pilier-card {
            flex: 0 1 280px;
            background-color: var(--vert-menthe);
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0px 8px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .pilier-card h3 {
            font-family: 'Pacifico', cursive;
            color: var(--rose-corail);
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .pilier-card p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--gris-fonce);
        }

        .section-texte {
            background-color: var(--rose-corail);
            color: white;
            padding: 40px 30px;
            border-radius: 15px;
            margin-top: 50px;
            text-align: center;
        }

        .section-texte h2 {
            font-family: 'Pacifico', cursive;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .section-texte p {
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
        }

        .section-texte a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-bottom: 2px solid white;
            transition: all 0.3s ease;
        }

        .section-texte a:hover {
            background-color: rgba(255,255,255,0.1);
            padding-bottom: 3px;
        }

        .feuille-route {
            background-color: rgba(133, 214, 205, 0.2);
            padding: 40px;
            border-left: 5px solid var(--vert-menthe);
            margin-top: 50px;
            border-radius: 10px;
        }

        .feuille-route h3 {
            color: var(--rose-corail);
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .feuille-route ul {
            list-style: none;
            padding: 0;
        }

        .feuille-route li {
            padding: 8px 0;
            font-size: 1rem;
            line-height: 1.6;
        }

        .feuille-route li:before {
            content: "✨ ";
            color: var(--vert-menthe);
            font-weight: bold;
            margin-right: 8px;
        }

        .cta-rejoindre {
            margin-top: 40px;
        }

        .cta-rejoindre a {
            background-color: var(--vert-menthe);
            color: var(--gris-fonce);
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .cta-rejoindre a:hover {
            background-color: #6FC1B0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>

<?php include_once 'includes/footer.php'; ?>
