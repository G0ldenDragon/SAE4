import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import org.mindrot.jbcrypt.BCrypt; // Importez BCrypt

public class TournamentManagementLogin extends JFrame {

    private JTextField emailField;
    private JPasswordField passwordField;

    public TournamentManagementLogin() {
        setTitle("Login");
        setSize(300, 200);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        // Utilisation d'un gestionnaire de mise en page FlowLayout
        setLayout(new FlowLayout(FlowLayout.CENTER, 10, 10));

        JLabel emailLabel = new JLabel("Email:");
        add(emailLabel);

        emailField = new JTextField(20);
        add(emailField);

        JLabel passwordLabel = new JLabel("Mot de passe:");
        add(passwordLabel);

        passwordField = new JPasswordField(20);
        add(passwordField);

        JButton loginButton = new JButton("Se connecter");
        loginButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                String email = emailField.getText();
                String password = new String(passwordField.getPassword());

                // Vérifier les informations de connexion dans la base de données
                if (validateLogin(email, password)) {
                    JOptionPane.showMessageDialog(null, "Connexion réussie", "Succès", JOptionPane.INFORMATION_MESSAGE);

                    // Fermez la fenêtre de connexion actuelle
                    dispose();

                    // Ouvrez la fenêtre principale ici, par exemple :
                    TournamentManagementMain main = new TournamentManagementMain();
                    main.setVisible(true);
                } else {
                    JOptionPane.showMessageDialog(null, "Email ou mot de passe incorrect", "Erreur de connexion",
                            JOptionPane.ERROR_MESSAGE);
                }
            }
        });
        add(loginButton);
    }

    // Méthode pour valider les informations de connexion dans la base de données
    private boolean validateLogin(String email, String password) {
        try {
            String jdbcUrl = "jdbc:mysql://localhost:3306/sae_karting";
            String dbUser = "root";
            String dbPassword = "";

            Connection connection = DriverManager.getConnection(jdbcUrl, dbUser, dbPassword);

            String query = "SELECT * FROM user WHERE email = ?";
            PreparedStatement preparedStatement = connection.prepareStatement(query);
            preparedStatement.setString(1, email);

            ResultSet resultSet = preparedStatement.executeQuery();

            if (resultSet.next()) {
                String hashedPasswordFromDatabase = resultSet.getString("password"); // Obtenez le hachage depuis la
                                                                                     // base de données

                // Vérifiez le mot de passe avec BCrypt
                if (BCrypt.checkpw(password, hashedPasswordFromDatabase)) {
                    // L'utilisateur est authentifié
                    return true;
                }
            }

            resultSet.close();
            preparedStatement.close();
            connection.close();
        } catch (SQLException ex) {
            ex.printStackTrace();
        }

        return false;
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            TournamentManagementLogin login = new TournamentManagementLogin();
            login.setVisible(true);
        });
    }
}
