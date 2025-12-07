🟦 SwapSkillApp – Plateforme d’échange de compétences (Symfony)

SwapSkillApp est une application web développée avec Symfony permettant aux utilisateurs d’échanger des compétences, d’organiser des événements, de discuter via une messagerie interne et de s’évaluer après chaque échange.
Elle intègre plusieurs modules : utilisateurs, compétences, propositions d’échange, événements, notifications et système de rating.

📌 Fonctionnalités principales
👤 Gestion des utilisateurs

Inscription et connexion (formulaire + sécurité Symfony)

Rôles :

ROLE_USER : utilisateur standard

ROLE_ADMIN : accès à l’administration

Mise à jour du profil

🧠 Gestion des compétences (Skills)

Ajout / édition / suppression de compétences

Recherche de compétences

Liaison compétence ↔ utilisateur

🔁 Propositions d’échange (Exchange Proposals)

Un utilisateur peut proposer un échange de compétence à un autre utilisateur.

Statuts : pending, accepted, refused

Historique des échanges

💬 Messagerie interne

Envoi de messages entre utilisateurs

Filtrage et conversation en temps réel (architecture prévue)

⭐ Système de notation (Rating)

Un utilisateur peut noter un autre utilisateur après un échange

Score sur 5 + commentaire

Moyenne des évaluations visible sur le profil

📅 Événements (Events)

Création d’événements (workshops, conférences…)

Inscriptions des utilisateurs

Capacité maximale

Statuts d’inscription : confirmed, pending

🔔 Notifications

Notification lors d’un nouvel échange, message ou rating

Stockage en base
