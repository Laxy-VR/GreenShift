<div class="content">
    <h1>Add Availability</h1>
    <form action="PHP/add_availability.php" method="post">
        <div>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
        </div>
        <div>
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" required>
        </div>
        <div>
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" required>
        </div>
        <button type="submit" class="btn btn-success">Add Availability</button>
    </form>
</div>
