const toggleSwitch = document.getElementById('toggleSwitch');
const pageTitle = document.getElementById('pageTitle');
const helpForm = document.getElementById('helpForm');


// Toggle switch functionality (changes page title, clears the fields, and shows the correct fields)
toggleSwitch.addEventListener('change', function () {
  if (this.checked) {
    pageTitle.textContent = 'Request a New University';
    showRequestFields();
  } else {
    pageTitle.textContent = 'Request Website Support';
    RemoveRequestFields();
  }
});


// Clears the fields and Removes the request fields (University Name, Unversity Address, and Any Other Info)
function RemoveRequestFields() {
  helpForm.reset();
  document.getElementById('universityName').parentElement.remove();
  document.getElementById('universityAddress').parentElement.remove();
  document.getElementById('otherInfo').parentElement.remove();
}


// Show the request fields (University Name, Unversity Address, and Any Other Info)
function showRequestFields() {
  const universityNameField = createFormField('University Name', 'universityName');
  const universityAddressField = createFormField('University Address', 'universityAddress');
  const otherInfoField = createTextarea('Any Other Necessary Information', 'otherInfo');

  helpForm.insertBefore(universityNameField, helpForm.lastElementChild);
  helpForm.insertBefore(universityAddressField, helpForm.lastElementChild);
  helpForm.insertBefore(otherInfoField, helpForm.lastElementChild);
  
}


// Accepts a Label and Id name and creates a label and an input field)
function createFormField(labelText, inputId) {
  const label = document.createElement('label');
  label.setAttribute('for', inputId);
  label.textContent = labelText;
  label.setAttribute('class', 'requestlabel');

  const input = document.createElement('input');
  input.setAttribute('type', 'text');
  input.setAttribute('id', inputId);
  input.setAttribute('name', inputId);
  input.setAttribute('required', '');
  input.setAttribute('class', 'requestinput');

  const field = document.createElement('div');
  field.appendChild(label);
  field.appendChild(input);

  return field;
}


// Does the same as above, but instead of creating an input tag, it creates a Textarea tag for the other info field
function createTextarea(labelText, inputId) {
  const label = document.createElement('label');
  label.setAttribute('for', inputId);
  label.textContent = labelText;
  label.setAttribute('class', 'requestlabel');

  const input = document.createElement('textarea');
  input.setAttribute('type', 'text');
  input.setAttribute('id', inputId);
  input.setAttribute('name', inputId);
  input.setAttribute('class', 'requestinput');
  input.setAttribute('style', 'resize: none;');

  const field = document.createElement('div');
  field.appendChild(label);
  field.appendChild(input);

  return field;
}