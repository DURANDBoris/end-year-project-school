import 'package:flutter/cupertino.dart';
import 'package:front/models/core/Document.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/helper/FolderHelper.dart';
import 'package:front/models/service/DocumentApi.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:provider/provider.dart';

class DocumentHelper {
  BuildContext context;
  late AuthenticationProvider authentication;
  late FolderHelper folderHelper;
  DocumentApi documentApi = DocumentApi();

  DocumentHelper({required this.context}) {
    authentication =
        Provider.of<AuthenticationProvider>(context, listen: false);
    folderHelper = FolderHelper(context: context, documentHelperStack: this);
  }

  Future<List<Document>?> getFolderDocuments({required Folder folder}) async {
    return await documentApi.getFolderDocument(
        idFolder: folder.id, authentication: authentication);
  }

  Future<void> deleteDocuments(
      {required List<Document> listDocument, required Folder folder}) async {
    for (Document document in listDocument) {
      await documentApi.delDocument(
        idDocument: document.id,
        authentication: authentication,
      );
    }
    folderHelper.getFolderContent(folder: folder);
  }
}
