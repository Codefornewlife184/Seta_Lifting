document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    let isSubmitting = false;

    // Önceki event listener'ları temizle
    const oldForm = form.cloneNode(true);
    form.parentNode.replaceChild(oldForm, form);
    
    oldForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (isSubmitting) return;
        isSubmitting = true;

        const formData = new FormData(this);
        
        try {
            const response = await fetch('send_mail.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            alert(data.message);
            
            if(data.status === 'success') {
                this.reset();
                window.location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.');
        } finally {
            isSubmitting = false;
        }
    });
});