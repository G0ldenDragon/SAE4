import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.text.SimpleDateFormat;

public class CreerCourse extends JDialog {

    // Déclaration des composants de l'interface utilisateur
    private JTextField courseNameField;
    private JTextField locationField;
    private JFormattedTextField timeSlotField;
    private JButton createCourseButton;

    public CreerCourse(Frame owner) {
        super(owner, "Create Course", true);

        setTitle("Create Course");
        setSize(400, 200);
        setLayout(new FlowLayout());
        setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);

        // Initialisation des champs de texte et du bouton
        courseNameField = new JTextField(20);
        locationField = new JTextField(20);
        timeSlotField = new JFormattedTextField(new SimpleDateFormat("HH:mm"));
        timeSlotField.setColumns(5);

        createCourseButton = new JButton("Create Course");
        createCourseButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                createCourse();
            }
        });

        // Ajout des composants à la fenêtre
        add(new JLabel("Course Name:"));
        add(courseNameField);
        add(new JLabel("Location:"));
        add(locationField);
        add(new JLabel("Time Slot (HH:mm):"));
        add(timeSlotField);
        add(createCourseButton);
    }

    // Méthode pour créer une nouvelle course dans la base de données
    void createCourse() {
        String courseName = courseNameField.getText();
        String location = locationField.getText();
        String timeSlot = timeSlotField.getText();

        if (courseName.isEmpty() || location.isEmpty() || timeSlot.isEmpty()) {
            JOptionPane.showMessageDialog(this, "Veuillez remplir les champs.");
            return;
        }

        // Insérer la nouvelle course dans la base de données
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "");
                PreparedStatement stmt = conn
                        .prepareStatement("INSERT INTO courses (nom_piste, lieu_piste, horaire) VALUES (?, ?, ?)")) {

            stmt.setString(1, courseName);
            stmt.setString(2, location);
            stmt.setString(3, timeSlot);
            int rowsAffected = stmt.executeUpdate();

            if (rowsAffected > 0) {
                JOptionPane.showMessageDialog(this, "La course a bien été crée.");
                dispose(); // Ferme la fenêtre de dialogue
            } else {
                JOptionPane.showMessageDialog(this, "Erreur lors de la création.");
            }
        } catch (SQLException e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Erreur lors de la création.");
        }
    }

    public JTextField getCourseNameField() {
        return courseNameField;
    }

    public JTextField getLocationField() {
        return locationField;
    }

    public JFormattedTextField getTimeSlotField() {
        return timeSlotField;
    }

    public JButton getCreateCourseButton() {
        return createCourseButton;
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            CreerCourse createCourseDialog = new CreerCourse(null);
            createCourseDialog.setVisible(true);
        });
    }
}
