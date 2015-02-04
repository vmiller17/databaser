package dbtLab3;

import java.sql.*;

/**
 * Database is a class that specifies the interface to the movie database. Uses
 * JDBC and the MySQL Connector/J driver.
 */
public class Database {
	/**
	 * The database connection.
	 */
	private Connection conn;

	/**
	 * Create the database interface object. Connection to the database is
	 * performed later.
	 */
	public Database() {
		conn = null;
	}

	/**
	 * Open a connection to the database, using the specified user name and
	 * password.
	 * 
	 * @param userName
	 *            The user name.
	 * @param password
	 *            The user's password.
	 * @return true if the connection succeeded, false if the supplied user name
	 *         and password were not recognized. Returns false also if the JDBC
	 *         driver isn't found.
	 */
	public boolean openConnection(String userName, String password) {
		try {
			Class.forName("com.mysql.jdbc.Driver");
			conn = DriverManager.getConnection(
					"jdbc:mysql://puccini.cs.lth.se/" + userName, userName,
					password);
		} catch (SQLException e) {
			e.printStackTrace();
			return false;
		} catch (ClassNotFoundException e) {
			e.printStackTrace();
			return false;
		}
		return true;
	}

	/**
	 * Close the connection to the database.
	 */
	public void closeConnection() {
		try {
			if (conn != null) {
				conn.close();
			}
		} catch (SQLException e) {
		}
		conn = null;
	}

	/**
	 * Check if the connection to the database has been established
	 * 
	 * @return true if the connection has been established
	 */
	public boolean isConnected() {
		return conn != null;
	}

	// Here Victor has started to add his methods.

	/**
	* Login to GUI.  
	*
	* @return true if logged in, else false.
	*/
	public boolean login(String username) {
		return false

	}


	/**
	* Makes a reservation for a movie performance.
	*
	* @return true if a reservation is made. False if not.
	*/
	public boolean makeReservation(String date, Sting movie, String username) {
		return false
	}


	/**
	* Gets the list of movies that are available for viewing.
	*
	* @return List<String> with the list of movies availible. Null if no moview are availible.
	*/
	public List<String> getMovies() {
		return null
	}


	/**
	* Returns all performances concerning a certain title.
	* @return List<Performance> if there is any performances. Null if none. 
	*/
	public List<Performance> getPerfomances(String title) {
		return null
	} 

	/* --- insert own code here --- */

}
