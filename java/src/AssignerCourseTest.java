import org.junit.Before;
import org.junit.Test;

import javax.swing.*;
import java.util.Vector;

import static org.junit.Assert.*;

public class AssignerCourseTest {

    private AssignerCourse assignerCourse;

    @Before
    public void setUp() {
        // Initialisation de l'objet AssignerCourse pour chaque test
        assignerCourse = new AssignerCourse(null, new Vector<>());
    }

    @Test
    public void testAssignCoursesToCompetition() {
        // Test de la méthode assignCoursesToCompetition
        JComboBox<String> competitionComboBox = assignerCourse.getCompetitionComboBox();
        JList<String> courseList = assignerCourse.getCourseList();

        // Sélectionnez la première compétition et le premier cours
        competitionComboBox.setSelectedIndex(0);
        courseList.setSelectedIndex(0);

        assignerCourse.assignCoursesToCompetition();
    }

    @Test
    public void testGetCourseIdByName() {
        // Test de la méthode getCourseIdByName

        // Remplacez "Nom du cours" par le nom d'un cours existant dans votre base de
        // données
        String courseNameToFind = "Paul Ricard";

        // Insérez ici l'ID attendu correspondant au nom du cours dans votre base de
        // données
        int expectedCourseId = 3;

        int courseId = assignerCourse.getCourseIdByName(courseNameToFind);

        // Vérifiez que l'ID du cours est correct
        assertEquals(expectedCourseId, courseId);
    }

    @Test
    public void testLoadCompetitions() {
        assignerCourse.loadCompetitions();
        JComboBox<String> competitionComboBox = assignerCourse.getCompetitionComboBox();
        Vector<Integer> competitionIds = assignerCourse.getCompetitionIds();

        assertNotNull(competitionComboBox);
        assertNotNull(competitionIds);
        assertTrue(competitionComboBox.getItemCount() > 0);
        assertTrue(competitionIds.size() > 0);
    }

    @Test
    public void testLoadCourses() {
        assignerCourse.loadCourses();
        JList<String> courseList = assignerCourse.getCourseList();
        DefaultListModel<String> courseListModel = assignerCourse.getCourseListModel();

        assertNotNull(courseList);
        assertNotNull(courseListModel);
        assertTrue(courseListModel.getSize() > 0);
    }
}
