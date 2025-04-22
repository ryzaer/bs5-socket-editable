const ws = new WebSocket("ws://localhost:8080");

ws.onmessage = function (resp) {
    console.log(resp);
    if(resp.data == "update_user") 
        fetch("fetch_users.php")
        .then((res) => res.text())
        .then((html) => {
            document.getElementById("userTableBody").innerHTML = html;
        });
};