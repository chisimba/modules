package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.button.Button;

import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;

/**
 *
 * @author nguni
 */
public class ContactDetails {
    private Dialog newResourcesDialog = new Dialog();
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private final TextField<String> H1 = new TextField<String>();
    private final TextField<String> H2a = new TextField<String>();
    private final TextField<String> H2b = new TextField<String>();
    private final TextField<String> H3a = new TextField<String>();
    private final TextField<String> H3b = new TextField<String>();
    private Button saveButton = new Button("Finish");
    
    public ContactDetails() {
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(700);
        mainForm.setLabelWidth(400);

        H1.setFieldLabel("H.1 Name of academic proposing the course/unit");
        H1.setAllowBlank(false);
        H1.setName("H1");

        H2a.setFieldLabel("H.2.a Name of the School which will be the home for the course/unit");
        H2a.setAllowBlank(false);
        H2a.setName("H2a");

        H2b.setFieldLabel("H.2.b School approval signature (Head of School or appropriate School committee chair) and date");
        H2b.setAllowBlank(false);
        H2b.setName("H2b");

        H3a.setFieldLabel("H.3.a Telephone contact numbers");
        H3a.setAllowBlank(false);
        H3a.setName("H3a");

        H3b.setFieldLabel("H.3.b Email addresses");
        H3b.setAllowBlank(false);
        H3b.setName("H3b");

        mainForm.add(H1, formData);
        mainForm.add(H2a, formData);
        mainForm.add(H2b, formData);
        mainForm.add(H3a, formData);
        mainForm.add(H3b, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {

            }
        });
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        newResourcesDialog.setBodyBorder(false);
        newResourcesDialog.setHeading("Section H: Contact and Details");
        newResourcesDialog.setWidth(800);
        //newResourcesDialog.setHeight(450);
        newResourcesDialog.setHideOnButtonClick(true);
        newResourcesDialog.setButtons(Dialog.CLOSE);
        newResourcesDialog.setButtonAlign(HorizontalAlignment.LEFT);

        newResourcesDialog.add(mainForm);
    }

    public void show() {
        newResourcesDialog.show();
    }
}