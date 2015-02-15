<?php
class Performance {
  private $date;
  private $movie;
  private $theaterName;
  private $availableSeats;

  public function __construct($date, $movie, $theaterName, $availableSeats) {
    $this->date = $date;
    $this->movie = $movie;
    $this->theaterName = $theaterName;
    $this->availableSeats = $availableSeats;
  }

  public function getDate() {
    return $this->date;
  }

  public function getMovie() {
    return $this->movie;
  }

  public function getTheaterName() {
    return $this->theaterName;
  }

  public function getAvailableSeats() {
    return $this->availableSeats;
  }

}


?>
