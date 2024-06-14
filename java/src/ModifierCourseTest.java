import org.junit.Before;
import org.junit.Test;

import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JTextField;

import static org.junit.Assert.*;

public class ModifierCourseTest {
    private ModifierCourse manageCourses;

    @Before
    public void setUp() {
        // Initialisation de l'objet ModifierCourse pour chaque test
        manageCourses = new ModifierCourse();
    }

    @Test
    public void testDeleteCourse() {
        JComboBox<String> courseComboBox = manageCourses.getCourseComboBox();
        assertNotNull(courseComboBox);

        // Sélectionnez une course factice pour la suppression
        courseComboBox.setSelectedItem("Imola");

        // Obtenez le bouton de suppression
        JButton deleteButton = manageCourses.getDeleteButton();
        assertNotNull(deleteButton);

        // Appelez la méthode deleteCourse
        deleteButton.doClick();

        // Vérifiez si la liste des courses a été rafraîchie après la suppression
        int itemCount = courseComboBox.getItemCount();
        assertEquals(0, itemCount);
    }

    @Test
    public void testLoadCourses() {
        JComboBox<String> courseComboBox = manageCourses.getCourseComboBox();
        assertNotNull(courseComboBox);

        // Appelez la méthode de chargement des courses
        manageCourses.loadCourses();

        // Vérifiez si la liste des courses a été mise à jour
        int itemCount = courseComboBox.getItemCount();
        assertTrue(itemCount > 0);
    }

    @Test
    public void testMoveCourse() {
        JComboBox<String> courseComboBox = manageCourses.getCourseComboBox();
        assertNotNull(courseComboBox);

        // Sélectionnez une course factice à déplacer
        courseComboBox.setSelectedItem("Course à déplacer");

        // Obtenez le bouton de déplacement
        JButton moveButton = manageCourses.getMoveButton();
        assertNotNull(moveButton);

        // Appelez la méthode moveCourse
        moveButton.doClick();

        // Vérifiez si la liste des courses a été rafraîchie après le déplacement
        int itemCount = courseComboBox.getItemCount();
        assertEquals(0, itemCount);
    }

    @Test
    public void testUpdateCourse() {
        JTextField courseNameField = manageCourses.getCourseNameField();
        JTextField locationField = manageCourses.getLocationField();
        JTextField startTimeField = manageCourses.getStartTimeField();

        assertNotNull(courseNameField);
        assertNotNull(locationField);
        assertNotNull(startTimeField);

        // Sélectionnez une course factice à mettre à jour
        JComboBox<String> courseComboBox = manageCourses.getCourseComboBox();
        assertNotNull(courseComboBox);
        courseComboBox.setSelectedItem("Course à mettre à jour");

        // Simulez la saisie de données dans les champs
        courseNameField.setText("Nouveau nom de course");
        locationField.setText("Nouvel emplacement");
        startTimeField.setText("12:00");

        // Obtenez le bouton de mise à jour
        JButton updateButton = manageCourses.getUpdateButton();
        assertNotNull(updateButton);

        // Appelez la méthode updateCourse
        updateButton.doClick();

        // Vérifiez si la liste des courses a été rafraîchie après la mise à jour
        int itemCount = courseComboBox.getItemCount();
        assertTrue(itemCount > 0);
    }
}
