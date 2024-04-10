document.getElementById("searchText").addEventListener("keyup", function(event) {
    if (event.key === "Enter") {
        document.getElementById("searchuser").submit();
    }
});