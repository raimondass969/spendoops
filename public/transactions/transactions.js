function deleteTransaction(id){
    const token = document.querySelector('input[name="csrf_token"]').value;
    fetch(`delete.php?id=${id}`, {
        method:'DELETE',
        headers:{
            'CSRF-TOKEN': token ,
        }
    
    })
        .then(response => response.json())
        .then(data => {
        if(data.success){
            document.querySelector(`#transaction-${id}`).remove();
            alert(data.message)
            return true;
        }else{
            alert(data.message)
            return false;
        }
        })
}
