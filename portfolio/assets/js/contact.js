/*=============== EMAIL JS ===============*/
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) => {
    e.preventDefault()

   // serviceID - templateID - #form - publicKey
    emailjs.sendForm('service_weygn4g','template_5u2skzj','#contact-form','OV2l7lT_n_usXGLyh')
    .then(() =>{
        // Show sent message
        contactMessage.textContent = 'Message sent successfully ✅'

         // Remove message after five seconds
         setTimeout(() => {
         contactMessage.textContent = ''
         },5000)

         // Clear input fields
         contactForm.reset()
    }, () => {
        // Show error message
        contactMessage.textContent = 'Message not sent (service error) ❌'
    }) 
}

contactForm.addEventListener('submit', sendEmail)