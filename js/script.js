document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    let currentPage = 1;

    function loadUsers(page = 1, query = "") {
        currentPage = page;
        fetch(`fetch_users.php?page=${page}&search=${encodeURIComponent(query)}`)
            .then((res) => res.text())
            .then((data) => {
                document.getElementById("userTableBody").innerHTML = data;
            });
    }

    searchInput.addEventListener("input", function () {
        loadUsers(1, this.value);
    });

    // Global function for pagination
    window.goToPage = function (page) {
        loadUsers(page, searchInput.value);
    };

    loadUsers(); // initial load

    // Add user
    const addForm = document.getElementById("addUserForm");
    addForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(addForm);
        fetch("add_user.php", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.text())
            .then((result) => {
                if (result === "success") {
                    addForm.reset();
                    bootstrap.Modal.getInstance(document.getElementById("addUserModal")).hide();
                    notifyWebSocket("update_user");
                    loadUsers();
                }
            });
    });

    // Edit user
    const editForm = document.getElementById("editUserForm");
    editForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(editForm);
        fetch("edit_user.php", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.text())
            .then((result) => {
                if (result === "success") {
                    bootstrap.Modal.getInstance(document.getElementById("editUserModal")).hide();
                    notifyWebSocket("update_user");
                    loadUsers();
                }
            });
    });

    window.openEditModal = function (id, name, email) {
        document.getElementById("editId").value = id;
        document.getElementById("editName").value = name;
        document.getElementById("editEmail").value = email;
        new bootstrap.Modal(document.getElementById("editUserModal")).show();
    };

    window.deleteUser = function (id) {
        if (confirm("Are you sure you want to delete this user?")) {
            const formData = new FormData();
            formData.append("id", id);
            fetch("delete_user.php", {
                method: "POST",
                body: formData,
            })
                .then((res) => res.text())
                .then((result) => {
                    if (result === "success") {
                        notifyWebSocket("update_user");
                        loadUsers();
                    }
                });
        }
    };

    window.notifyWebSocket = function (msg) {
        if (typeof ws !== "undefined" && ws.readyState === WebSocket.OPEN)
            ws.send(msg);
        
    };
});