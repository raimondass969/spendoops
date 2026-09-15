function deleteTransaction(id) {
    const token = document.querySelector('input[name="csrf_token"]').value;
    fetch(`delete.php?id=${id}`, {
        method: 'DELETE',
        headers: {
            'CSRF-TOKEN': token,
        }
    })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.message);
                }

                 return data;
            });
        })
        .then(data =>{ 
            if(data.success) {
                document.querySelector(`#transaction-${id}`).remove();
            } 

            alert(data.message);
        })
         .catch(error => {
            alert(error.message);
        });
}
