window.onload = function() {

    document.getElementById('EGBoilerplatePostType').addEventListener('change', function(e) {
        const select = e.target;
        const value = select.value;
        const desc = select.selectedOptions[0].text;

        const itemsToChange = document.querySelectorAll("#EGBoilerplateWrap .change_post_type");

        itemsToChange.forEach((el) => {
          el.textContent = desc;
        });

        const EGBoilerplateButton = document.getElementById('EGBoilerplateButton');
        EGBoilerplateButton.value = "Create " + desc;
        

    });

}