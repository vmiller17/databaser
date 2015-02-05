package dbtLab3;
import java.util.*;
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

	// Here Victor and Johanes has started to add their methods.

	/**
	* Login to GUI.  
	*
	* @return true if logged in, else false.
	*/
	public boolean login(String username) {
		String sql = "select count(*) c " 
				+ "from Users "
				+ "where username = ?";

    
		boolean success = false;
		try {
			PreparedStatement ps = conn.prepareStatement(sql);
			ps.setString(1, username);
			ResultSet rs = ps.executeQuery();
			rs.next();
			success = rs.getInt("c") == 1;
		} catch (SQLException e) {
			e.printStackTrace();
		}
    
		return success;
	}



	/**
	* Makes a reservation for a movie performance.
	*
	* @return int resrvationNbr if a reservation is made. -1 if not.
	*/
	public int makeReservation(String date, String movie, String username) {

		int result = 0;

		// Make sure it is possible to make a reservation.
		try {
			String sql = "select bookings,nbrOfSeats from Performances,Theaters "
					+ "where date = ? and movieTitle = ? "
					+ "and Performances.theaterName = Theaters.name";
			PreparedStatement ps = conn.prepareStatement(sql);
			ps.setString(1, date);
			ps.setString(2, movie);
			ResultSet rs = ps.executeQuery();
			rs.next();
			if (rs.getInt("bookings") >= rs.getInt("nbrOfSeats")) return -1;
		} catch (SQLException e) {
			e.printStackTrace();
		}

		// Insert a reservation into the Reservations table 
		try {
			String sql = "insert into Reservations(userUsername, performanceDate, performanceMovieTitle) " 
					+ "values(?,?,?)";
			PreparedStatement ps = conn.prepareStatement(sql);
			ps.setString(1, username);
			ps.setString(2, date);
			ps.setString(3, movie);
			result = ps.executeUpdate();
		} catch (SQLException e) {
			e.printStackTrace();
		}
		if (result==0) return -1;

		// Update the Performances table
		try {
			String sql = "update Performances "          // Here you wrote Reservations instead.
					+ "set bookings = bookings + 1 "
					+ "where date = ? and movieTitle = ?";
			PreparedStatement ps = conn.prepareStatement(sql);
			ps.setString(1, date);
			ps.setString(2, movie);
			result = ps.executeUpdate();
		} catch (SQLException e) {
			e.printStackTrace();
		}
		if (result==0) return -1;

		// Return the reservation number for the reservation
		try {
			String sql = "select max(resNbr) reservationNbr "          // Here you wrote Reservations instead.
					+ "from Reservations "
					+ "where userUsername = ? "
					+ "and performanceDate = ? "
					+ "and performanceMovieTitle = ?";
			PreparedStatement ps = conn.prepareStatement(sql);
			ps.setString(1, username);
			ps.setString(2, date);
			ps.setString(3, movie);
			ResultSet rs = ps.executeQuery();
			rs.next();
			result = rs.getInt("reservationNbr");
		} catch (SQLException e) {
			e.printStackTrace();
		}
		
		if (result > 0) {
			return result;
		} else {
			return -1;
		}
	}


	/**
	* Gets the list of movies that are available for viewing.
	*
	* @return List<String> with the list of movies availible. Null if no moview are availible.
	*/
	public List<String> getMovies() {

		// String sql = "select movieTitle "
		//   + "from Performances";

		String sql = "select title " 
				+ "from Movies";

		List<String> movieList = new ArrayList<String>();
		try {
			PreparedStatement ps = conn.prepareStatement(sql);
			ResultSet rs = ps.executeQuery();
			
			while (rs.next()) {
				movieList.add(rs.getString("title"));
			}
			
		} catch (SQLException e) {
			e.printStackTrace();
		}

		return movieList;
	}


	/**
	* Returns all performances concerning a certain title.
	* @return List<Performance> if there is any performances. Null if none. 
	*/
	public List<Performance> getPerfomances(String title) {
		String sql = "select movieTitle, date, theaterName, bookings, nbrOfSeats "
				+ "from Performances,Theaters "
				+ "where movieTitle = ? "
				+ "and Performances.theaterName = Theaters.name";

		
		List<Performance> performanceList = new ArrayList<Performance>();
		try {
		PreparedStatement ps = conn.prepareStatement(sql);
		ps.setString(1, title);
		ResultSet rs = ps.executeQuery();
		

		while (rs.next()) {
			performanceList.add(new Performance(rs.getString("date")
					, rs.getString("movieTitle")
					, rs.getString("theaterName")
					, rs.getInt("nbrOfSeats") - rs.getInt("bookings")));
		}
		} catch (SQLException e) {
			e.printStackTrace();
		}

		return performanceList;
	} 


	public Performance getPerfomance(String movieName, String date) {
		String sql = "select theaterName, bookings, nbrOfSeats "
				+ "from Performances,Theaters "
				+ "where movieTitle = ? "
				+ "and date = ? "
				+ "and Performances.theaterName = Theaters.name";
		
		
		Performance performance = null;
		
		try {
			PreparedStatement ps = conn.prepareStatement(sql);
			ps.setString(1, movieName);
			ps.setString(2, date);
			ResultSet rs = ps.executeQuery();
			
			
			rs.next();
			String theaterName = rs.getString("theaterName");
			int availableSeats  = rs.getInt("nbrOfSeats") - rs.getInt("bookings");
			performance = new Performance(date, movieName, theaterName, availableSeats);

		} catch (SQLException e) {
			e.printStackTrace(); 
		}

		return performance;

	}
}
