import org.junit.Before;
import org.junit.Test;

import javax.swing.JButton;
import javax.swing.JFormattedTextField;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JTextField;

import static org.junit.Assert.*;

public class CreerCourseTest {
    private CreerCourse createCourseDialog;

    @Before
    public void setUp() {
        // Initialisation de l'objet CreerCourse pour chaque test
        createCourseDialog = new CreerCourse(new JFrame());
    }

    @Test
    public void testCreateCourse() {
        // Initialisation des champs de texte
        JTextField courseNameField = createCourseDialog.getCourseNameField();
        JTextField locationField = createCourseDialog.getLocationField();
        JFormattedTextField timeSlotField = createCourseDialog.getTimeSlotField();

        assertNotNull(courseNameField);
        assertNotNull(locationField);
        assertNotNull(timeSlotField);

        // Simuler la saisie de données dans les champs
        courseNameField.setText("Nom de la course");
        locationField.setText("Emplacement de la course");
        timeSlotField.setText("10:00");

        // Obtenez le bouton de création de course
        JButton createCourseButton = createCourseDialog.getCreateCourseButton();
        assertNotNull(createCourseButton);

        // Appelez la méthode createCourse
        createCourseButton.doClick();

        // Vérifiez si la fenêtre de dialogue a été fermée
        assertFalse(createCourseDialog.isVisible());
    }
}
