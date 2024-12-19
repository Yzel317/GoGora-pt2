//Author: Justine Lucas
const editButtons = document.querySelectorAll('.edit-ride');
const editModal = document.getElementById('editRideModal');

// Check if the modal and buttons exist before adding event listeners
if (editButtons.length > 0 && editModal) {
  editButtons.forEach((button) => {
    button.addEventListener('click', async () => {
      const rideId = button.dataset.rideId; // Assume data-ride-id is set in the HTML
      try {
        const response = await fetch(`/api/rides/${rideId}`);
        
        if (!response.ok) {
          throw new Error('Failed to fetch ride details');
        }
        
        const ride = await response.json();

        // Populate the form fields
        document.getElementById('editPlateNumber').value = ride.plate_number;
        document.getElementById('editTypeOfRide').value = ride.ride_type;
        document.getElementById('editSeatingCapacity').value = ride.capacity;
        document.getElementById('editRoute').value = ride.route;
        document.getElementById('editSchedule').value = ride.departure;

        // Save ride ID for updating later
        document.getElementById('updateRideButton').dataset.rideId = rideId;
        editModal.style.display = 'block';
      } catch (error) {
        console.error('Failed to fetch ride details', error);
      }
    });
  });

  // Update Ride
  document.getElementById('updateRideButton').addEventListener('click', async () => {
    const rideId = document.getElementById('updateRideButton').dataset.rideId;
    const updatedData = new FormData();
    updatedData.append('plate_number', document.getElementById('editPlateNumber').value);
    updatedData.append('ride_type', document.getElementById('editTypeOfRide').value);
    updatedData.append('capacity', document.getElementById('editSeatingCapacity').value);
    updatedData.append('route', document.getElementById('editRoute').value);
    updatedData.append('departure', document.getElementById('editSchedule').value);
    const image = document.getElementById('editRideImage').files[0];
    if (image) updatedData.append('image', image);

    try {
      const response = await fetch(`/api/rides/${rideId}`, {
        method: 'PUT',
        body: updatedData,
      });
      const result = await response.json();
      if (response.ok) {
        alert('Ride updated successfully!');
        location.reload();
      } else {
        alert(`Failed to update ride: ${result.error}`);
      }
    } catch (error) {
      console.error('Error updating ride:', error);
    }
  });

  // Close the edit modal
  document.getElementById('closeEditModalButton').addEventListener('click', () => {
    editModal.style.display = 'none';
  });
}
