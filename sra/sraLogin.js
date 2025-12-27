document.addEventListener("DOMContentLoaded", function() {
  const loginForm = document.getElementById("login");
  const errMsg = document.getElementById("err-msg");
  loginForm.addEventListener("submit", function formValidation(e){
    if(loginForm.id.value.trim() === ""){
      e.preventDefault();
      loginForm.id.focus();
      errMsg.textContent = "ID cannot be left blank";
      return;
    }
    else if (loginForm.pw.value.trim() === ""){
      e.preventDefault();
      loginForm.pw.focus();
      errMsg.textContent = "Password cannot be left blank";
      return;
    }
  });  
});