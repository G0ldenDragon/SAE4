import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Vector;

public class AssignerCourse extends JDialog {

    // Déclaration des composants de l'interface utilisateur
    private JComboBox<String> competitionComboBox;
    private JList<String> courseList;
    private JButton assignButton;

    // Vecteur pour stocker les IDs des compétitions
    private Vector<Integer> competitionIds;

    // Modèle par défaut pour la liste des cours
    private DefaultListModel<String> courseListModel;

    // Constructeur de la classe AssignerCourse
    public AssignerCourse(Frame owner, Vector<Integer> competitionIds) {
        super(owner, "Assign Courses to Competition", true);
        this.competitionIds = competitionIds;

        // Configuration de la fenêtre de dialogue
        setTitle("Assigner Courses");
        setSize(400, 300);
        setLayout(new FlowLayout());
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);

        // Initialisation des composants de l'interface utilisateur
        competitionComboBox = new JComboBox<>();
        courseList = new JList<>();
        assignButton = new JButton("Assigner Courses");

        // Création du modèle par défaut pour la liste des cours
        courseListModel = new DefaultListModel<>();
        courseList.setModel(courseListModel);

        // Définition du mode de sélection pour permettre les sélections multiples
        courseList.setSelectionMode(ListSelectionModel.MULTIPLE_INTERVAL_SELECTION);

        // Chargement des compétitions et des cours depuis la base de données
        loadCompetitions();
        loadCourses();

        // Ajout des composants à la fenêtre de dialogue
        add(new JLabel("Sélectionner une compétition:"));
        add(competitionComboBox);
        add(new JLabel("Sélectionner les courses:"));
        add(new JScrollPane(courseList));
        add(assignButton);

        // Configuration de l'action du bouton d'attribution de cours
        assignButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                assignCoursesToCompetition();
            }
        });
    }

    public JComboBox<String> getCompetitionComboBox() {
        return competitionComboBox;
    }

    public JList<String> getCourseList() {
        return courseList;
    }

    public DefaultListModel<String> getCourseListModel() {
        return courseListModel;
    }

    public Vector<Integer> getCompetitionIds() {
        return competitionIds;
    }

    // Méthode pour charger les compétitions depuis la base de données
    void loadCompetitions() {
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "");
                PreparedStatement stmt = conn.prepareStatement("SELECT compet_id, nom_compet FROM compétitions");
                ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                int competitionId = rs.getInt("compet_id");
                String competitionName = rs.getString("nom_compet");
                competitionComboBox.addItem(competitionName);
                competitionIds.add(competitionId); // Ajout des IDs de compétition au vecteur
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Méthode pour charger les cours depuis la base de données
    void loadCourses() {
        courseListModel.clear(); // Effacement du modèle de la liste des cours

        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "");
                PreparedStatement stmt = conn.prepareStatement("SELECT course_id, nom_piste FROM courses");
                ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                int courseId = rs.getInt("course_id");
                String courseName = rs.getString("nom_piste");
                courseListModel.addElement(courseName); // Ajout des noms de cours au modèle
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    // Méthode pour attribuer des cours à une compétition
    void assignCoursesToCompetition() {
        String selectedCompetition = (String) competitionComboBox.getSelectedItem();

        if (selectedCompetition == null) {
            JOptionPane.showMessageDialog(this, "Please select a competition.");
            return;
        }

        int competitionId = competitionIds.get(competitionComboBox.getSelectedIndex());
        int[] selectedIndices = courseList.getSelectedIndices();

        if (selectedIndices == null || selectedIndices.length == 0) {
            JOptionPane.showMessageDialog(this, "Please select one or more courses.");
            return;
        }

        // Attribuer les cours sélectionnés à la compétition dans la base de données
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "");
                PreparedStatement stmt = conn.prepareStatement(
                        "INSERT INTO compétitions_has_courses (compet_id, course_id) VALUES (?, ?)")) {

            for (int selectedIndex : selectedIndices) {
                String courseName = courseListModel.getElementAt(selectedIndex);
                int courseId = getCourseIdByName(courseName);

                stmt.setInt(1, competitionId);
                stmt.setInt(2, courseId);
                stmt.executeUpdate();
            }

            JOptionPane.showMessageDialog(this, "Courses assigned to the competition successfully.");
            dispose(); // Fermer la fenêtre de dialogue
        } catch (SQLException e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error assigning courses to the competition.");
        }
    }

    // Méthode pour obtenir l'ID d'un cours par son nom
    int getCourseIdByName(String courseName) {
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "");
                PreparedStatement stmt = conn.prepareStatement("SELECT course_id FROM courses WHERE nom_piste = ?")) {

            stmt.setString(1, courseName);
            ResultSet rs = stmt.executeQuery();

            if (rs.next()) {
                return rs.getInt("course_id");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return -1; // Retourner -1 si le cours n'est pas trouvé (gérer ce cas d'erreur de manière
                   // plus appropriée)
    }

    // Méthode main pour tester la classe AssignerCourse
    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            AssignerCourse assignerCourseDialog = new AssignerCourse(null, new Vector<>());
            assignerCourseDialog.setVisible(true);
        });
    }
}
