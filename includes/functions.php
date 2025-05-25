<?php

// Make sure config.php is included so we have the $pdo database connection
require_once 'config.php';

// --- HELPER FUNCTIONS ---

/**
 * Redirects to a specified URL.
 * @param string $url The URL to redirect to.
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Checks if an admin is logged in.
 * @return bool True if logged in, false otherwise.
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// --- ADMIN USER FUNCTIONS ---

/**
 * Authenticates an admin user.
 * @param string $username The username to check.
 * @param string $password The plain text password.
 * @return bool True on success, false on failure.
 */
function authenticateAdmin($username, $password) {
    global $pdo; // Get the $pdo connection from config.php

    $stmt = $pdo->prepare("SELECT id, username, password FROM AdminUser WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct! Start a session.
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_id'] = $user['id'];
        return true;
    }
    return false;
}

/**
 * Logs out the admin user.
 */
function logoutAdmin() {
    session_unset();   // Remove all session variables
    session_destroy(); // Destroy the session
    redirect('/training-management/admin/pages/login.php'); // Redirect to login page
}

// --- CRUD FUNCTIONS FOR TABLES (Admin Panel) ---

// --- Pays (Country) Functions ---
function getAllPays() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Pays ORDER BY nom");
    return $stmt->fetchAll();
}

function getPaysById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Pays WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addPays($nom) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Pays (nom) VALUES (?)");
        $stmt->execute([$nom]);
        return true;
    } catch (PDOException $e) {
        // Handle duplicate entry error or other errors
        return false;
    }
}

function updatePays($id, $nom) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Pays SET nom = ? WHERE id = ?");
        $stmt->execute([$nom, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deletePays($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM Pays WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- Ville (City) Functions ---
function getAllVilles() {
    global $pdo;
    $stmt = $pdo->query("SELECT v.*, p.nom as pays_nom FROM Ville v JOIN Pays p ON v.pays_id = p.id ORDER BY v.nom");
    return $stmt->fetchAll();
}

function getVilleById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Ville WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addVille($nom, $pays_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Ville (nom, pays_id) VALUES (?, ?)");
        $stmt->execute([$nom, $pays_id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateVille($id, $nom, $pays_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Ville SET nom = ?, pays_id = ? WHERE id = ?");
        $stmt->execute([$nom, $pays_id, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteVille($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM Ville WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- Formateur (Trainer) Functions ---
function getAllFormateurs() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Formateur ORDER BY nom, prenom");
    return $stmt->fetchAll();
}

function getFormateurById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Formateur WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addFormateur($prenom, $nom, $email, $telephone, $photo = NULL) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Formateur (prenom, nom, email, telephone, photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$prenom, $nom, $email, $telephone, $photo]);
        return true;
    } catch (PDOException $e) {
        // Handle duplicate email error
        return false;
    }
}

function updateFormateur($id, $prenom, $nom, $email, $telephone, $photo = NULL) {
    global $pdo;
    try {
        $sql = "UPDATE Formateur SET prenom = ?, nom = ?, email = ?, telephone = ?";
        $params = [$prenom, $nom, $email, $telephone];
        if ($photo !== NULL) {
            $sql .= ", photo = ?";
            $params[] = $photo;
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteFormateur($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM Formateur WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- Domaine (Domain) Functions ---
function getAllDomaines() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM Domaine ORDER BY nom");
    return $stmt->fetchAll();
}

function getDomaineById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Domaine WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addDomaine($nom, $description) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Domaine (nom, description) VALUES (?, ?)");
        $stmt->execute([$nom, $description]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateDomaine($id, $nom, $description) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Domaine SET nom = ?, description = ? WHERE id = ?");
        $stmt->execute([$nom, $description, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteDomaine($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM Domaine WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- Sujet (Subject) Functions ---
function getAllSujets() {
    global $pdo;
    $stmt = $pdo->query("SELECT s.*, d.nom as domaine_nom FROM Sujet s JOIN Domaine d ON s.domaine_id = d.id ORDER BY s.nom");
    return $stmt->fetchAll();
}

function getSujetById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Sujet WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addSujet($nom, $description_courte, $description_longue, $avantage_business, $logo_image = NULL, $domaine_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Sujet (nom, description_courte, description_longue, avantage_business, logo_image, domaine_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $description_courte, $description_longue, $avantage_business, $logo_image, $domaine_id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateSujet($id, $nom, $description_courte, $description_longue, $avantage_business, $logo_image = NULL, $domaine_id) {
    global $pdo;
    try {
        $sql = "UPDATE Sujet SET nom = ?, description_courte = ?, description_longue = ?, avantage_business = ?, domaine_id = ?";
        $params = [$nom, $description_courte, $description_longue, $avantage_business, $domaine_id];
        if ($logo_image !== NULL) {
            $sql .= ", logo_image = ?";
            $params[] = $logo_image;
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteSujet($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM Sujet WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- Cours (Course) Functions ---
function getAllCours() {
    global $pdo;
    $stmt = $pdo->query("SELECT c.*, s.nom as sujet_nom FROM Cours c JOIN Sujet s ON c.sujet_id = s.id ORDER BY c.nom");
    return $stmt->fetchAll();
}

function getCoursById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM Cours WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addCours($nom, $description_courte, $description_longue, $duree, $contenu_test, $logique, $sujet_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Cours (nom, description_courte, description_longue, duree, contenu_test, logique, sujet_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $description_courte, $description_longue, $duree, $contenu_test, $logique, $sujet_id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateCours($id, $nom, $description_courte, $description_longue, $duree, $contenu_test, $logique, $sujet_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Cours SET nom = ?, description_courte = ?, description_longue = ?, duree = ?, contenu_test = ?, logique = ?, sujet_id = ? WHERE id = ?");
        $stmt->execute([$nom, $description_courte, $description_longue, $duree, $contenu_test, $logique, $sujet_id, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteCours($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM Cours WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- Formation (Training) Functions ---
function getAllFormations() {
    global $pdo;
    // This query joins multiple tables to get all necessary details for display
    $stmt = $pdo->query("
        SELECT f.*,
               c.nom as cours_nom, c.description_courte as cours_description_courte,
               s.nom as sujet_nom,
               d.nom as domaine_nom,
               frm.prenom as formateur_prenom, frm.nom as formateur_nom
        FROM Formation f
        JOIN Cours c ON f.cours_id = c.id
        JOIN Sujet s ON c.sujet_id = s.id
        JOIN Domaine d ON s.domaine_id = d.id
        LEFT JOIN Formateur frm ON f.formateur_id = frm.id
        ORDER BY c.nom
    ");
    return $stmt->fetchAll();
}

function getFormationById($id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT f.*,
               c.nom as cours_nom, c.description_courte as cours_description_courte, c.description_longue as cours_description_longue, c.duree as cours_duree, c.contenu_test as cours_contenu_test, c.logique as cours_logique,
               s.nom as sujet_nom, s.description_courte as sujet_description_courte, s.description_longue as sujet_description_longue, s.avantage_business as sujet_avantage_business, s.logo_image as sujet_logo_image,
               d.nom as domaine_nom, d.description as domaine_description,
               frm.prenom as formateur_prenom, frm.nom as formateur_nom, frm.email as formateur_email, frm.telephone as formateur_telephone, frm.photo as formateur_photo
        FROM Formation f
        JOIN Cours c ON f.cours_id = c.id
        JOIN Sujet s ON c.sujet_id = s.id
        JOIN Domaine d ON s.domaine_id = d.id
        LEFT JOIN Formateur frm ON f.formateur_id = frm.id
        WHERE f.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addFormation($description_inscription, $mode, $formateur_id, $cours_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Formation (description_inscription, mode, formateur_id, cours_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$description_inscription, $mode, $formateur_id, $cours_id]);
        return $pdo->lastInsertId(); // Returns the ID of the new formation
    } catch (PDOException $e) {
        return false;
    }
}

function updateFormation($id, $description_inscription, $mode, $formateur_id, $cours_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Formation SET description_inscription = ?, mode = ?, formateur_id = ?, cours_id = ? WHERE id = ?");
        $stmt->execute([$description_inscription, $mode, $formateur_id, $cours_id, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteFormation($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM Formation WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- FormationDate Functions ---
function getFormationDatesByFormationId($formation_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM FormationDate WHERE formation_id = ? ORDER BY date");
    $stmt->execute([$formation_id]);
    return $stmt->fetchAll();
}

function getFormationDateById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM FormationDate WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addFormationDate($formation_id, $date) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO FormationDate (formation_id, date) VALUES (?, ?)");
        $stmt->execute([$formation_id, $date]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateFormationDate($id, $date) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE FormationDate SET date = ? WHERE id = ?");
        $stmt->execute([$date, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deleteFormationDate($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM FormationDate WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// --- PrixFormation (Training Price) Functions ---
function getPrixFormationsByFormationId($formation_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM PrixFormation WHERE formation_id = ? ORDER BY prix");
    $stmt->execute([$formation_id]);
    return $stmt->fetchAll();
}

function getPrixFormationById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM PrixFormation WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addPrixFormation($formation_id, $prix, $description = NULL) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO PrixFormation (formation_id, prix, description) VALUES (?, ?, ?)");
        $stmt->execute([$formation_id, $prix, $description]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updatePrixFormation($id, $prix, $description = NULL) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE PrixFormation SET prix = ?, description = ? WHERE id = ?");
        $stmt->execute([$prix, $description, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function deletePrixFormation($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM PrixFormation WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}


// --- Inscription (Enrollment) Functions (Client Side) ---
function addInscription($prenom, $nom, $email, $telephone, $adresse, $formation_date_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO Inscription (prenom, nom, email, telephone, adresse, formation_date_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$prenom, $nom, $email, $telephone, $adresse, $formation_date_id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function getAllInscriptions() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT i.*, fd.date as formation_date, f.description_inscription as formation_description, c.nom as cours_nom
        FROM Inscription i
        JOIN FormationDate fd ON i.formation_date_id = fd.id
        JOIN Formation f ON fd.formation_id = f.id
        JOIN Cours c ON f.cours_id = c.id
        ORDER BY fd.date DESC, i.nom, i.prenom
    ");
    return $stmt->fetchAll();
}

function updateInscriptionPaymentStatus($id, $paye) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Inscription SET paye = ? WHERE id = ?");
        $stmt->execute([$paye, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}


// --- CLIENT SIDE FUNCTIONS (Filtering and Searching) ---

/**
 * Get formations filtered by domain.
 * @param int $domaine_id The ID of the domain.
 * @return array List of formations.
 */
function getFormationsByDomaine($domaine_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT f.*,
               c.nom as cours_nom, c.description_courte as cours_description_courte,
               s.nom as sujet_nom,
               d.nom as domaine_nom,
               frm.prenom as formateur_prenom, frm.nom as formateur_nom
        FROM Formation f
        JOIN Cours c ON f.cours_id = c.id
        JOIN Sujet s ON c.sujet_id = s.id
        JOIN Domaine d ON s.domaine_id = d.id
        LEFT JOIN Formateur frm ON f.formateur_id = frm.id
        WHERE d.id = ?
        ORDER BY c.nom
    ");
    $stmt->execute([$domaine_id]);
    return $stmt->fetchAll();
}

/**
 * Get formations filtered by subject.
 * @param int $sujet_id The ID of the subject.
 * @return array List of formations.
 */
function getFormationsBySujet($sujet_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT f.*,
               c.nom as cours_nom, c.description_courte as cours_description_courte,
               s.nom as sujet_nom,
               d.nom as domaine_nom,
               frm.prenom as formateur_prenom, frm.nom as formateur_nom
        FROM Formation f
        JOIN Cours c ON f.cours_id = c.id
        JOIN Sujet s ON c.sujet_id = s.id
        JOIN Domaine d ON s.domaine_id = d.id
        LEFT JOIN Formateur frm ON f.formateur_id = frm.id
        WHERE s.id = ?
        ORDER BY c.nom
    ");
    $stmt->execute([$sujet_id]);
    return $stmt->fetchAll();
}

/**
 * Get formations filtered by course.
 * @param int $cours_id The ID of the course.
 * @return array List of formations.
 */
function getFormationsByCours($cours_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT f.*,
               c.nom as cours_nom, c.description_courte as cours_description_courte,
               s.nom as sujet_nom,
               d.nom as domaine_nom,
               frm.prenom as formateur_prenom, frm.nom as formateur_nom
        FROM Formation f
        JOIN Cours c ON f.cours_id = c.id
        JOIN Sujet s ON c.sujet_id = s.id
        JOIN Domaine d ON s.domaine_id = d.id
        LEFT JOIN Formateur frm ON f.formateur_id = frm.id
        WHERE c.id = ?
        ORDER BY c.nom
    ");
    $stmt->execute([$cours_id]);
    return $stmt->fetchAll();
}


/**
 * Get formations filtered by location (city).
 * This requires a link between Formation and City. We will assume for simplicity that a FormationDate
 * might imply a location. If a Formation truly has a fixed location, you might need a new table or field.
 * For now, let's assume filtering by location means filtering by the city associated with a specific formation date,
 * or by a generic location associated with the training itself. The diagram doesn't explicitly link Formation to Ville.
 * Let's adapt this based on the common use case where a Formation *session* has a location.
 *
 * IMPORTANT: The current schema doesn't directly link `Formation` to `Ville`.
 * If you need to filter by location, you'd likely add `ville_id` to `FormationDate` table.
 * For now, this function will return an empty array or require schema modification.
 *
 * Let's assume we add `ville_id` to `FormationDate` for this to work.
 * ALTER TABLE FormationDate ADD COLUMN ville_id INT;
 * ALTER TABLE FormationDate ADD FOREIGN KEY (ville_id) REFERENCES Ville(id) ON DELETE SET NULL;
 */
function getFormationsByLocation($ville_id) {
    global $pdo;
    // This query assumes you've added ville_id to FormationDate table
    $stmt = $pdo->prepare("
        SELECT DISTINCT f.*,
               c.nom as cours_nom, c.description_courte as cours_description_courte,
               s.nom as sujet_nom,
               d.nom as domaine_nom,
               frm.prenom as formateur_prenom, frm.nom as formateur_nom
        FROM Formation f
        JOIN Cours c ON f.cours_id = c.id
        JOIN Sujet s ON c.sujet_id = s.id
        JOIN Domaine d ON s.domaine_id = d.id
        LEFT JOIN Formateur frm ON f.formateur_id = frm.id
        JOIN FormationDate fd ON f.id = fd.formation_id
        WHERE fd.ville_id = ?
        ORDER BY c.nom
    ");
    $stmt->execute([$ville_id]);
    return $stmt->fetchAll();
}


/**
 * Gets all formation dates for the calendar.
 * @return array List of all formation dates with associated details.
 */
function getFormationCalendarDates() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT fd.id as formation_date_id, fd.date,
               f.id as formation_id, f.description_inscription, f.mode,
               c.nom as cours_nom,
               s.nom as sujet_nom,
               d.nom as domaine_nom,
               frm.prenom as formateur_prenom, frm.nom as formateur_nom
        FROM FormationDate fd
        JOIN Formation f ON fd.formation_id = f.id
        JOIN Cours c ON f.cours_id = c.id
        JOIN Sujet s ON c.sujet_id = s.id
        JOIN Domaine d ON s.domaine_id = d.id
        LEFT JOIN Formateur frm ON f.formateur_id = frm.id
        ORDER BY fd.date ASC
    ");
    return $stmt->fetchAll();
}

?>