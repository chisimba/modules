package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.button.Button;

import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.Radio;
import com.extjs.gxt.ui.client.widget.form.RadioGroup;
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;

/**
 *
 * @author nguni
 */
public class CollaborationAndContracts {

    private Dialog newResourcesDialog = new Dialog();
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private final RadioGroup F1a = new RadioGroup();
    private final Radio radioNo1 = new Radio();
    private final Radio radioYes1 = new Radio();
    private final Radio radioNo2 = new Radio();
    private final Radio radioYes2 = new Radio();
    private final TextField<String> F1b = new TextField<String>();
    private final RadioGroup F2a = new RadioGroup();
    private final TextField<String> F2b = new TextField<String>();
    private final TextField<String> F3a = new TextField<String>();
    private final TextField<String> F3b = new TextField<String>();
    private final TextField<String> F4 = new TextField<String>();
    private Button saveButton = new Button("Next");

    public CollaborationAndContracts() {
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(700);
        mainForm.setLabelWidth(400);

        radioNo1.setBoxLabel("No");
        radioYes1.setBoxLabel("Yes");
        radioYes1.setValue(true);
        radioNo2.setBoxLabel("No");
        radioYes2.setBoxLabel("Yes");
        radioYes2.setValue(true);

        F1a.setFieldLabel("F.1.a Is approval for the course/unit required from a professional body?");
        F1a.add(radioYes1);
        F1a.add(radioNo1);

        F1b.setFieldLabel("F.1.b If yes, state the name of the professional body and provide details of the body's prerequisites and/or contraints.");
        F1b.setAllowBlank(false);
        F1b.setName("F1b");

        F2a.setFieldLabel("F.2.a Are other Schools or Faculties involved in and/or have an interest in the course/unit?");
        F2a.add(radioYes2);
        F2a.add(radioNo2);

        F2b.setFieldLabel("F.2.b If yes, provide the details of the other School's or Faculties's involvement/interest, including support and provisioning for the course/unit.");
        F2b.setAllowBlank(false);
        F2b.setName("F2b");

        F3a.setFieldLabel("F.3.a Does the course/unit provide service learning?");
        F3a.setAllowBlank(false);
        F3a.setName("F3a");

        F3b.setFieldLabel("F.3.b If yes, provide the details on the nature as well as the provisioning for the service learning component and methodology.");
        F3b.setAllowBlank(false);
        F3b.setName("F3b");

        F4.setFieldLabel("F.4 Specify whether collaboratoin, contracts or other cooperatoin agreements have been, or will need to be, entered into with entities outside of the univerty?");
        F4.setAllowBlank(false);
        F4.setName("F4");

        mainForm.add(F1a, formData);
        mainForm.add(F1b, formData);
        mainForm.add(F2a, formData);
        mainForm.add(F2b, formData);
        mainForm.add(F3a, formData);
        mainForm.add(F3b, formData);
        mainForm.add(F4, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                Review review = new Review();
                review.show();
                newResourcesDialog.hide();
            }
        });
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        newResourcesDialog.setBodyBorder(false);
        newResourcesDialog.setHeading("Section F: Collaboration and Contracts");
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
