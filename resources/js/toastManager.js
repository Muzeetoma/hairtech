class ToastManager {
    constructor(toastSelector) {
      this.toastElement = document.querySelector(toastSelector);
      if (this.toastElement) {
        this.toastElement.style.display = 'block';
        this.toast = new bootstrap.Toast(this.toastElement);
      } else {
        console.error(`Toast element not found for selector: ${toastSelector}`);
      }
    }
  
    show() {
      if (this.toast) {
        this.toast.show();
      }
    }
  
    hide() {
      if (this.toast) {
        this.toast.hide();
      }
    }
  }
  
  export default ToastManager;
  