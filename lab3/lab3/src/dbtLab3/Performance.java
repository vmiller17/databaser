package dbtLab3;

public class Performance {

	private String date;
	private String movie;
	private String theaterName;
	private int availableSeats;


	public Performance(String date, String movie, String theaterName, int availableSeats) {
		this.date = date;
		this.movie = movie;
		this.theaterName = theaterName;
		this.availableSeats = availableSeats;

	}


	public String getDate() {
		return date;
	}

	public String getMovie() {
		return movie;
	}

	public String getTheaterName() {
		return theaterName;
	}

	public int getAvailableSeats() {
		return availableSeats;
	}










}