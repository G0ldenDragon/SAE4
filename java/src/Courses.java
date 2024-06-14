import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.Vector;
import java.sql.ResultSet;

public class Courses extends JFrame {

    // Déclaration des composants de l'interface utilisateur
    private JComboBox<String> competitionComboBox;
    private JComboBox<String> courseComboBox;
    private JComboBox<String> winnerComboBox;
    private JTextField laptimeField;

    public Courses() {
        setTitle("Sélectionner une compétition, une course, un gagnant et saisir le temps au tour");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(400, 250);

        JPanel panel = new JPanel();
        panel.setLayout(new GridLayout(5, 2));

        // Initialisation des composants
        competitionComboBox = new JComboBox<>();
        courseComboBox = new JComboBox<>();
        winnerComboBox = new JComboBox<>();
        laptimeField = new JTextField();

        // Chargement des données depuis la base de données
        loadCompetitions();
        loadCourses();
        loadParticipants();

        // Ajout des composants au panneau
        panel.add(new JLabel("Sélectionner une compétition:"));
        panel.add(competitionComboBox);
        panel.add(new JLabel("Sélectionner une course:"));
        panel.add(courseComboBox);
        panel.add(new JLabel("Sélectionner un gagnant:"));
        panel.add(winnerComboBox);
        panel.add(new JLabel("Temps au tour (mm:ss:SSS):"));
        panel.add(laptimeField);
        panel.add(new JLabel(""));
        JButton selectButton = new JButton("Sélectionner");
        panel.add(selectButton);

        // Configuration de l'action du bouton de sélection
        selectButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                String selectedCompetition = (String) competitionComboBox.getSelectedItem();
                String selectedCourse = (String) courseComboBox.getSelectedItem();
                String selectedWinner = (String) winnerComboBox.getSelectedItem();
                String laptime = laptimeField.getText();

                // Valider le format du temps au tour
                if (isValidLaptime(laptime)) {
                    // Mettre à jour la course avec le temps au tour validé
                    updateCourse(selectedCompetition, selectedCourse, selectedWinner, laptime);

                    JOptionPane.showMessageDialog(Courses.this, "Compétition sélectionnée : " + selectedCompetition
                            + "\nCourse sélectionnée : " + selectedCourse
                            + "\nGagnant sélectionné : " + selectedWinner
                            + "\nTemps au tour : " + laptime);
                } else {
                    JOptionPane.showMessageDialog(Courses.this, "Veuillez entrer un temps au tour valide (mm:ss:SSS).");
                }
            }
        });

        // Configuration de l'action lors de la sélection d'une compétition
        competitionComboBox.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                loadCoursesForCompetition();
                loadParticipantsForCompetition();
            }
        });

        // Ajout des composants à la fenêtre
        add(panel, BorderLayout.CENTER);
        add(selectButton, BorderLayout.SOUTH);
    }

    // Méthode pour valider le format du temps au tour
    boolean isValidLaptime(String laptime) {
        // Expression régulière pour correspondre au format mm:ss:SSS
        String pattern = "^[0-5][0-9]:[0-5][0-9]:[0-9]{3}$";
        return laptime.matches(pattern);
    }

    // Méthode pour charger les compétitions depuis la base de données
    void loadCompetitions() {
        competitionComboBox.removeAllItems();
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting",
                "root", "");
                PreparedStatement stmt = conn.prepareStatement("SELECT nom_compet FROM compétitions");
                ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                String competitionName = rs.getString("nom_compet");
                competitionComboBox.addItem(competitionName);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Méthode pour charger les cours depuis la base de données
    void loadCourses() {
        courseComboBox.removeAllItems();
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting",
                "root", "");
                PreparedStatement stmt = conn.prepareStatement("SELECT nom_piste FROM courses");
                ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                String courseName = rs.getString("nom_piste");
                courseComboBox.addItem(courseName);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Méthode pour charger les cours pour une compétition donnée depuis la base de
    // données
    void loadCoursesForCompetition() {
        String selectedCompetition = (String) competitionComboBox.getSelectedItem();
        if (selectedCompetition == null) {
            return;
        }

        courseComboBox.removeAllItems();

        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting",
                "root", "");
                PreparedStatement stmt = conn.prepareStatement("SELECT c.nom_piste " +
                        "FROM courses c " +
                        "INNER JOIN compétitions_has_courses chc ON c.course_id = chc.course_id " +
                        "INNER JOIN compétitions co ON chc.compet_id = co.compet_id " +
                        "WHERE co.nom_compet = ?");) {

            stmt.setString(1, selectedCompetition);
            ResultSet rs = stmt.executeQuery();

            while (rs.next()) {
                String courseName = rs.getString("nom_piste");
                courseComboBox.addItem(courseName);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Méthode pour charger les participants depuis la base de données
    void loadParticipants() {
        winnerComboBox.removeAllItems();
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting",
                "root", "");
                PreparedStatement stmt = conn.prepareStatement("SELECT name, firstname FROM user");
                ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                String participantNom = rs.getString("name");
                String participantPrenom = rs.getString("firstname");
                String participantNomPrenom = participantNom + " " + participantPrenom;
                winnerComboBox.addItem(participantNomPrenom);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Méthode pour charger les participants pour une compétition donnée depuis la
    // base de données
    void loadParticipantsForCompetition() {
        winnerComboBox.removeAllItems();

        String selectedCompetition = (String) competitionComboBox.getSelectedItem();
        if (selectedCompetition == null) {
            return;
        }

        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting",
                "root", "");
                PreparedStatement stmt = conn.prepareStatement(
                        "SELECT u.name, u.firstname " +
                                "FROM user u " +
                                "INNER JOIN user_has_compétitions uhc ON u.user_id = uhc.User_user_id " +
                                "INNER JOIN compétitions co ON uhc.Compétitions_compet_id = co.compet_id " +
                                "WHERE co.nom_compet = ?")) {

            stmt.setString(1, selectedCompetition);
            ResultSet rs = stmt.executeQuery();

            while (rs.next()) {
                String participantName = rs.getString("name");
                String participantFirstname = rs.getString("firstname");
                String participantNomPrenom = participantName + " " + participantFirstname;
                winnerComboBox.addItem(participantNomPrenom);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Méthode pour mettre à jour une course avec le gagnant et le temps au tour
    void updateCourse(String competition, String course, String winner, String laptime) {
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "");
                PreparedStatement stmt = conn.prepareStatement(
                        "UPDATE courses SET gagnant = ?, laptime = ? WHERE nom_piste = ?");) {

            stmt.setString(1, winner);
            stmt.setString(2, laptime);
            stmt.setString(3, course);

            int rowsUpdated = stmt.executeUpdate();
            if (rowsUpdated > 0) {
                JOptionPane.showMessageDialog(this, "Course mise à jour : " + course);
            } else {
                JOptionPane.showMessageDialog(this, "Course non trouvée : " + course);
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    public JComboBox<String> getCourseComboBox() {
        return courseComboBox;
    }

    public JComboBox<String> getWinnerComboBox() {
        return winnerComboBox;
    }

    public JComboBox<String> getCompetitionComboBox() {
        return competitionComboBox;
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            Courses courses = new Courses();
            courses.setVisible(true);
        });
    }
}
