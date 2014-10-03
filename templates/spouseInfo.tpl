{* template block that contains the new field *}
<fieldset class="crm-group spouseInfo-group" id="spouseInfo">
  <legend>{ts}Spouse Information{/ts}</legend>
  <div class="crm-section spouseInfo-section">
    <div class="label">{$form.spouseFirstName.label}</div>
    <div class="content">{$form.spouseFirstName.html}</div>
    <div class="clear"></div>
    <div class="label">{$form.spouseLastName.label}</div>
    <div class="content">{$form.spouseLastName.html}</div>
    <div class="clear"></div>
    <div class="label">{$form.spouseEmail.label}</div>
    <div class="content">{$form.spouseEmail.html}</div>
    <div class="clear"></div>
  </div>
</fieldset>

{* reposition the above block before #editrow-street_address-Primary *}
<script type="text/javascript">
  cj('#spouseInfo').insertAfter('#editrow-gender_id')
</script>
