import org.junit.Before;
import org.junit.Test;

import javax.swing.JComboBox;
import javax.swing.JTextField;

import static org.junit.Assert.*;

public class CoursesTest {
    private Courses courses;

    @Before
    public void setUp() {
        // Initialisation de l'objet Courses pour chaque test
        courses = new Courses();
    }

    @Test
    public void testIsValidLaptime() {
        assertTrue(courses.isValidLaptime("01:23:456")); // Temps au tour valide
        assertTrue(courses.isValidLaptime("00:00:001")); // Temps au tour valide
        assertFalse(courses.isValidLaptime("1:23:456")); // Temps au tour invalide (format incorrect)
        assertFalse(courses.isValidLaptime("01:23:45")); // Temps au tour invalide (format incorrect)
        assertFalse(courses.isValidLaptime("01:23:4567")); // Temps au tour invalide (format incorrect)
    }

    @Test
    public void testLoadCompetitions() {
        JComboBox<String> competitionComboBox = courses.getCompetitionComboBox();

        // Simuler le chargement de compétitions
        courses.loadCompetitions();

        assertNotNull(competitionComboBox);
        assertTrue(competitionComboBox.getItemCount() > 0);
    }

    @Test
    public void testLoadCourses() {
        JComboBox<String> courseComboBox = courses.getCourseComboBox();

        // Simuler le chargement de cours
        courses.loadCourses();

        assertNotNull(courseComboBox);
        assertTrue(courseComboBox.getItemCount() > 0);
    }

    @Test
    public void testLoadCoursesForCompetition() {
        JComboBox<String> competitionComboBox = courses.getCompetitionComboBox();
        JComboBox<String> courseComboBox = courses.getCourseComboBox();

        // Simuler la sélection d'une compétition
        competitionComboBox.setSelectedItem("Nom de la compétition");

        // Appelez la méthode de chargement des cours pour la compétition sélectionnée
        courses.loadCoursesForCompetition();

        // Vérifiez si la JComboBox des cours a été mise à jour avec les cours de la
        // compétition sélectionnée
        assertNotNull(courseComboBox);
        assertTrue(courseComboBox.getItemCount() > 0);
    }

    @Test
    public void testLoadParticipants() {
        JComboBox<String> winnerComboBox = courses.getWinnerComboBox();

        // Simuler le chargement de participants
        courses.loadParticipants();

        assertNotNull(winnerComboBox);
        assertTrue(winnerComboBox.getItemCount() > 0);
    }

    @Test
    public void testLoadParticipantsForCompetition() {
        JComboBox<String> competitionComboBox = courses.getCompetitionComboBox();
        JComboBox<String> winnerComboBox = courses.getWinnerComboBox();

        // Simuler la sélection d'une compétition
        competitionComboBox.setSelectedItem("Nom de la compétition");

        // Appelez la méthode de chargement des participants pour la compétition
        // sélectionnée
        courses.loadParticipantsForCompetition();

        // Vérifiez si la JComboBox des gagnants a été mise à jour avec les participants
        // de la compétition sélectionnée
        assertNotNull(winnerComboBox);
        assertTrue(winnerComboBox.getItemCount() > 0);
    }

    @Test
    public void testUpdateCourse() {
        String competition = "Tournois Annuel des 3 chaises";
        String course = "Paul Ricard";
        String winner = "PLATEAU Lucas";
        String laptime = "01:21:841";

        // Appelez la méthode updateCourse avec les données de test
        courses.updateCourse(competition, course, winner, laptime);
    }
}
