
import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.ResultSet;

public class ModifierCourse extends JFrame {

    private JComboBox<String> courseComboBox;
    private JTextField courseNameField;
    private JTextField locationField;
    private JTextField startTimeField;
    private JButton updateButton;
    private JButton moveButton;
    private JButton deleteButton;

    public ModifierCourse() {
        setTitle("Gérer les courses");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(400, 300);

        JPanel panel = new JPanel();
        panel.setLayout(new GridLayout(6, 2));

        courseComboBox = new JComboBox<>();
        courseNameField = new JTextField();
        locationField = new JTextField();
        startTimeField = new JTextField();

        loadCourses();

        panel.add(new JLabel("Sélectionner une course:"));
        panel.add(courseComboBox);
        panel.add(new JLabel("Nom de la course:"));
        panel.add(courseNameField);
        panel.add(new JLabel("Lieu de la course:"));
        panel.add(locationField);
        panel.add(new JLabel("Heure de départ:"));
        panel.add(startTimeField);

        updateButton = new JButton("Modifier");
        deleteButton = new JButton("Supprimer");
        moveButton = new JButton("Déplacer");

        panel.add(updateButton);
        panel.add(deleteButton);
        panel.add(moveButton);

        updateButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                updateCourse();
            }
        });

        deleteButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                deleteCourse();
            }
        });

        moveButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                moveCourse();
            }
        });

        add(panel, BorderLayout.CENTER);
    }

    void loadCourses() {
        courseComboBox.removeAllItems();
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "");
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

    void updateCourse() {
        String selectedCourse = (String) courseComboBox.getSelectedItem();
        String courseName = courseNameField.getText();
        String location = locationField.getText();
        String startTime = startTimeField.getText();

        if (selectedCourse == null || courseName.isEmpty() || location.isEmpty()
                || startTime.isEmpty()) {
            JOptionPane.showMessageDialog(this, "Veuillez remplir tous les champs obligatoires.");
            return;
        }

        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "")) {
            conn.setAutoCommit(false); // Désactiver l'auto-commit pour démarrer une transaction

            try (PreparedStatement stmt = conn.prepareStatement(
                    "UPDATE courses " +
                            "SET nom_piste = ?, lieu_piste = ?, horaire = ? " +
                            "WHERE nom_piste = ?")) {

                stmt.setString(1, courseName);
                stmt.setString(2, location);
                stmt.setString(3, startTime);
                stmt.setString(4, selectedCourse);

                int rowsAffected = stmt.executeUpdate();

                if (rowsAffected > 0) {
                    conn.commit(); // Valider la transaction si la mise à jour réussit
                    JOptionPane.showMessageDialog(this, "Course mise à jour avec succès.");
                    loadCourses(); // Rafraîchissez la liste des courses
                } else {
                    conn.rollback(); // Annuler la transaction en cas d'échec
                    JOptionPane.showMessageDialog(this, "Échec de la mise à jour de la course.");
                }
            } catch (SQLException ex) {
                conn.rollback(); // Annuler la transaction en cas d'exception
                ex.printStackTrace();
            } finally {
                conn.setAutoCommit(true); // Réactiver l'auto-commit
            }
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }

    void deleteCourse() {
        String selectedCourse = (String) courseComboBox.getSelectedItem();

        if (selectedCourse == null) {
            JOptionPane.showMessageDialog(this, "Veuillez sélectionner une course.");
            return;
        }

        int confirmation = JOptionPane.showConfirmDialog(this, "Êtes-vous sûr de vouloir supprimer cette course ?");

        if (confirmation == JOptionPane.YES_OPTION) {
            try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "")) {
                conn.setAutoCommit(false); // Désactiver l'auto-commit pour démarrer une transaction

                try (PreparedStatement stmt = conn.prepareStatement(
                        "DELETE FROM courses WHERE nom_piste = ?")) {

                    stmt.setString(1, selectedCourse);

                    int rowsAffected = stmt.executeUpdate();

                    if (rowsAffected > 0) {
                        conn.commit(); // Valider la transaction si la suppression réussit
                        JOptionPane.showMessageDialog(this, "Course supprimée avec succès.");
                        loadCourses(); // Rafraîchissez la liste des courses
                    } else {
                        conn.rollback(); // Annuler la transaction en cas d'échec
                        JOptionPane.showMessageDialog(this, "Échec de la suppression de la course.");
                    }
                } catch (SQLException ex) {
                    conn.rollback(); // Annuler la transaction en cas d'exception
                    ex.printStackTrace();
                } finally {
                    conn.setAutoCommit(true); // Réactiver l'auto-commit
                }
            } catch (SQLException ex) {
                ex.printStackTrace();
            }
        }
    }

    void moveCourse() {
        String selectedCourse = (String) courseComboBox.getSelectedItem();

        if (selectedCourse == null) {
            JOptionPane.showMessageDialog(this, "Veuillez sélectionner une course.");
            return;
        }

        String newCompetition = JOptionPane.showInputDialog(this, "Saisissez le nom de la nouvelle compétition:");

        if (newCompetition != null && !newCompetition.isEmpty()) {
            try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/sae_karting", "root", "")) {
                conn.setAutoCommit(false);

                try (PreparedStatement updateStmt = conn.prepareStatement(
                        "UPDATE compétitions_has_courses chc1 " +
                                "INNER JOIN courses c ON c.course_id = chc1.course_id " +
                                "INNER JOIN compétitions co1 ON chc1.compet_id = co1.compet_id " +
                                "INNER JOIN compétitions co2 ON co2.nom_compet = ? " +
                                "SET chc1.compet_id = co2.compet_id " +
                                "WHERE c.nom_piste = ?")) {

                    updateStmt.setString(1, newCompetition);
                    updateStmt.setString(2, selectedCourse);

                    int rowsAffected = updateStmt.executeUpdate();

                    if (rowsAffected > 0) {
                        conn.commit();
                        JOptionPane.showMessageDialog(this,
                                "Course déplacée avec succès vers la nouvelle compétition.");
                        loadCourses();
                    } else {
                        conn.rollback();
                        JOptionPane.showMessageDialog(this, "Échec du déplacement de la course.");
                    }
                } catch (SQLException ex) {
                    conn.rollback();
                    ex.printStackTrace();
                } finally {
                    conn.setAutoCommit(true);
                }
            } catch (SQLException ex) {
                ex.printStackTrace();
            }
        }
    }

    public JComboBox<String> getCourseComboBox() {
        return courseComboBox;
    }

    public JTextField getCourseNameField() {
        return courseNameField;
    }

    public JTextField getLocationField() {
        return locationField;
    }

    public JTextField getStartTimeField() {
        return startTimeField;
    }

    public JButton getUpdateButton() {
        return updateButton;
    }

    public JButton getDeleteButton() {
        return deleteButton;
    }

    public JButton getMoveButton() {
        return moveButton;
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            ModifierCourse manageCourses = new ModifierCourse();
            manageCourses.setVisible(true);
        });
    }

}
