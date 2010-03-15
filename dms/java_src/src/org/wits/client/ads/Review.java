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
public class Review {

    private Dialog newResourcesDialog = new Dialog();
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private final TextField<String> G1a = new TextField<String>();
    private final TextField<String> G1b = new TextField<String>();
    private final TextField<String> G2a = new TextField<String>();
    private final TextField<String> G2b = new TextField<String>();
    private final TextField<String> G3a = new TextField<String>();
    private final TextField<String> G3b = new TextField<String>();
    private final TextField<String> G4a = new TextField<String>();
    private final TextField<String> G4b = new TextField<String>();
    private Button saveButton = new Button("Next");

    public Review() {
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(700);
        mainForm.setLabelWidth(400);

        G1a.setFieldLabel("G.1.a How will the course/unit syllabus be reviewed?");
        G1a.setAllowBlank(false);
        G1a.setName("G1a");

        G1b.setFieldLabel("G.1.b  How often will the course/unit syllabus be reviewed?");
        G1b.setAllowBlank(false);
        G1b.setName("G1b");

        G2a.setFieldLabel("G.2.a How will the integration of course/unit outcomes, syllabus, teaching methods and assessment methods be evaluated? ");
        G2a.setAllowBlank(false);
        G2a.setName("G2a");

        G2b.setFieldLabel("G.2.b How often will the above integration be evaluated?");
        G2b.setAllowBlank(false);
        G2b.setName("G2b");

        G3a.setFieldLabel("G.3.a How will the course/unit through-put rate be evaluated?");
        G3a.setAllowBlank(false);
        G3a.setName("G3a");

        G3b.setFieldLabel("G.3.b How often will the course/unit through-put rate be evaluated?");
        G3b.setAllowBlank(false);
        G3b.setName("G3b");

        G4a.setFieldLabel("G.4.a How will the teaching on the course/unit be evaluated from a student perspective and from the lecturer’s perspective?");
        G4a.setAllowBlank(false);
        G4a.setName("G4a");

        G4b.setFieldLabel("G.4.b How often will the teaching on the course/unit be evaluated from these two perspectives?");
        G4b.setAllowBlank(false);
        G4b.setName("G4b");

        mainForm.add(G1a, formData);
        mainForm.add(G1b, formData);
        mainForm.add(G2a, formData);
        mainForm.add(G2b, formData);
        mainForm.add(G3a, formData);
        mainForm.add(G3b, formData);
        mainForm.add(G4a, formData);
        mainForm.add(G4b, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                ContactDetails contactDetails = new ContactDetails();
                contactDetails.show();
                newResourcesDialog.hide();
            }
        });
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        newResourcesDialog.setBodyBorder(false);
        newResourcesDialog.setHeading("Section G: Review");
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
