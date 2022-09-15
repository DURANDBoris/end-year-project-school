import 'package:flutter/material.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/views/homePage/dialog/CreateOrganizationDialog.dart';
import 'package:front/views/homePage/dialog/organizationDialog/OrganizationDialog.dart';
import 'package:front/views/homePage/dialog/UpdateAccountDialog.dart';
import '/providers/HomePageProvider.dart';
import 'package:provider/provider.dart';

void showSettingOverlay(BuildContext context) {
  HomePageProvider homePageProvider = Provider.of(context, listen: false);
  AuthenticationProvider authentication = Provider.of(context, listen: false);
  OverlayState? overlayState = Overlay.of(context);
  late OverlayEntry overlayEntry;
  overlayEntry = OverlayEntry(builder: (context) {
    return Stack(
      children: [
        SizedBox(
          height: double.infinity,
          width: double.infinity,
          child: ModalBarrier(
            onDismiss: (() {
              overlayEntry.remove();
            }),
          ),
        ),
        Positioned(
          right: 10,
          top: 51,
          child: Material(
            child: Container(
              width: 200,
              alignment: Alignment.center,
              padding: EdgeInsets.all(10),
              child: Column(
                children: [
                  ListTile(
                    title: Text("Account"),
                    onTap: () {
                      UpdateAccountDialog(context);
                      overlayEntry.remove();
                    },
                  ),
                  ListTile(
                    title: Text("Organization"),
                    onTap: () {
                      OrganizationDialog(context: context);
                      overlayEntry.remove();
                    },
                  ),
                  ListTile(
                    title: Text("Disconnect"),
                    onTap: () {
                      authentication.token = "";
                      overlayEntry.remove();
                      homePageProvider.folder = null;
                      homePageProvider.document = null;
                    },
                  ),
                ],
              ),
            ),
          ),
        )
      ],
    );
  });
  overlayState!.insert(overlayEntry);
}
