import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.util.Vector;

public class TournamentManagementMain extends JFrame {

    private JMenuBar menuBar;
    private JMenu menu;
    private JMenuItem creerCourseItem;
    private JMenuItem assignerCourseItem;
    private JMenuItem coursesItem;
    private JMenuItem modifierCoursesItem;
    private Vector<Integer> competitionIds;

    public TournamentManagementMain() {

        // Configuration de la fenêtre principale
        setTitle("Tournament Management");
        setSize(600, 300);
        setLayout(new BorderLayout());
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        // Création de la barre de menu
        menuBar = new JMenuBar();

        // Création du menu "Menu"
        menu = new JMenu("Menu");
        menuBar.add(menu);

        competitionIds = new Vector<>();

        // Création des éléments de menu
        creerCourseItem = new JMenuItem("Créer une course");
        creerCourseItem.addActionListener(this::createCourseAction);

        assignerCourseItem = new JMenuItem("Assigner une course à un tournoi");
        assignerCourseItem.addActionListener(this::assignCoursesAction);

        coursesItem = new JMenuItem("Courses");
        coursesItem.addActionListener(this::coursesAction);

        modifierCoursesItem = new JMenuItem("Modifier Courses");
        modifierCoursesItem.addActionListener(this::modifierCoursesAction);

        // Ajout des éléments de menu au menu "Menu"
        menu.add(creerCourseItem);
        menu.add(assignerCourseItem);
        menu.add(coursesItem);
        menu.add(modifierCoursesItem);

        // Définition de la barre de menu
        setJMenuBar(menuBar);
    }

    private void createCourseAction(ActionEvent event) {
        // Action lors de la sélection de "Créer une course"
        CreerCourse creerCourse = new CreerCourse(this);
        creerCourse.setVisible(true);
    }

    private void assignCoursesAction(ActionEvent event) {
        // Action lors de la sélection de "Assigner une course à un tournoi"
        AssignerCourse assignerCourse = new AssignerCourse(this, competitionIds);
        assignerCourse.setVisible(true);
    }

    private void coursesAction(ActionEvent event) {
        // Action lors de la sélection de "Courses"
        Courses courses = new Courses();
        courses.setVisible(true);
    }

    private void modifierCoursesAction(ActionEvent event) {
        // Action lors de la sélection de "Modifier Courses"
        ModifierCourse modifierCourse = new ModifierCourse();
        modifierCourse.setVisible(true);
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            // Création de la page principale de gestion de tournoi
            TournamentManagementMain mainPage = new TournamentManagementMain();
            mainPage.setVisible(true);
        });
    }
}
